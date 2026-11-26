FROM php:8.3-fpm

# Install system dependencies & Nginx
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip nginx

# Install Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Build dependencies tanpa menjalankan skrip database
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs --no-scripts
RUN npm install
RUN npm run build

# Konfigurasi Nginx untuk mengarahkan public folder Laravel
RUN echo 'server { \
    listen 8080; \
    root /app/public; \
    index index.php index.html; \
    location / { \
        try_files $uri $uri/ /index.php?$query_string; \
    } \
    location ~ \.php$ { \
        include fastcgi_params; \
        fastcgi_pass 127.0.0.1:9000; \
        fastcgi_index index.php; \
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; \
    } \
}' > /etc/nginx/sites-available/default

EXPOSE 8080

# Jalankan PHP-FPM dan Nginx secara bersamaan menggunakan port dinamis Railway
CMD sed -i "s/listen 8080;/listen ${PORT:-8080};/g" /etc/nginx/sites-available/default && service nginx start && php-fpm