CargoTrace ✈️📦

CargoTrace is a professional, enterprise-grade logistics and cargo tracking system built with Laravel. It features a clean "Corporate Blue & White" UI, real-time shipment routing, and an extensive global airport database.

✨ Key Features

Global Airport Database: Pre-loaded with over 5,000+ international airport locations for precise origin and destination routing.

Smart Auto-Registration: Seamless login experience. If an email is not found in the system, it automatically registers the user as a 'Customer' and logs them in instantly.

Role-Based Access Control (RBAC):

Admin: Full access to manage shipments, locations, users, and view system audit logs.

Customer: Simplified dashboard to track personal cargo.

System Audit Log: Secure tracking of all administrative actions (CREATE, UPDATE, DELETE) for accountability and transparency.

Premium UI/UX: A highly polished, responsive "Corporate Blue" design powered by Tailwind CSS.

🛠️ Tech Stack

Backend: Laravel (PHP)

Frontend: Blade Templating, Tailwind CSS, Chart.js

Database: MySQL

Authentication: Laravel Breeze (Customized)

🚀 Installation & Setup

Follow these steps to run CargoTrace on your local machine:

Clone the repository

git clone https://github.com/yourusername/cargotrace.git
cd cargotrace


Install PHP dependencies

composer install


Set up the environment

cp .env.example .env
php artisan key:generate


Make sure to update your .env file with your local database credentials (DB_DATABASE, DB_USERNAME, DB_PASSWORD).

Run Migrations and Seeders
(Important: The seeder is required to populate the 5,000+ airport locations)

php artisan migrate --seed


Run the application

php artisan serve


You can now access the app at http://localhost:8000.

💡 How to Log In

CargoTrace uses a Seamless Auto-Registration system:

Simply go to the Login page and enter your email and a password.

If you don't have an account, the system will automatically create one for you as a Customer and log you in immediately.

To access the Admin dashboard, you can manually update your role to admin in your database or use the default admin credentials provided in your seeder.

📄 License

This project is open-source and available under the MIT License.