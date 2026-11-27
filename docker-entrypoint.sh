#!/bin/sh
set -e

mkdir -p \
    /app/storage/framework/cache \
    /app/storage/framework/sessions \
    /app/storage/framework/views \
    /app/storage/logs \
    /app/bootstrap/cache

# Auto-detect DB_CONNECTION from DB_URL / DATABASE_URL when DB_CONNECTION is unset
if [ -z "${DB_CONNECTION:-}" ]; then
    DB_DSN="${DB_URL:-${DATABASE_URL:-}}"
    case "$DB_DSN" in
        postgres://*|postgresql://*)
            export DB_CONNECTION=pgsql
            ;;
        mysql://*)
            export DB_CONNECTION=mysql
            ;;
        mariadb://*)
            export DB_CONNECTION=mariadb
            ;;
    esac
fi

# Fallback to writable /tmp/database.sqlite if using sqlite and default file is missing
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    export DB_CONNECTION=sqlite
    if [ -z "${DB_DATABASE:-}" ] || [ ! -f "${DB_DATABASE}" ]; then
        export DB_DATABASE=/tmp/database.sqlite
        if [ ! -f "$DB_DATABASE" ]; then
            touch "$DB_DATABASE"
        fi
    fi
fi

# Run migrations and initial seed when users table is empty (non-fatal on transient DB errors)
if php artisan migrate --force --no-interaction; then
    USER_COUNT=$(php -r '
        require "/app/vendor/autoload.php";
        $app = require "/app/bootstrap/app.php";
        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        try {
            echo Illuminate\Support\Facades\DB::table("users")->count();
        } catch (Throwable $e) {
            echo "-1";
        }
    ' 2>/dev/null || echo "-1")

    if [ "$USER_COUNT" = "0" ]; then
        php artisan db:seed --force --no-interaction || echo "Warning: DatabaseSeeder failed"
    fi
else
    echo "Warning: database migration skipped or failed at container startup"
fi

exec "$@"
