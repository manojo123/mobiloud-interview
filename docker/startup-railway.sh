#!/bin/bash

# Railway Startup Script for Laravel Application (SQLite)
set -e

echo "🚀 Starting Laravel Application on Railway..."

# Check available PHP extensions
echo "🔍 Checking available PHP extensions..."
echo "Available PDO drivers:"
php -m | grep pdo || echo "No PDO drivers found"

# Check if SQLite PDO driver is available
echo "🔍 Checking SQLite extensions..."
if php -m | grep -q sqlite3; then
    echo "✅ SQLite3 extension is available"
    echo "ℹ️ Using 'sqlite' driver (not 'pdo_sqlite')"
elif php -m | grep -q pdo_sqlite; then
    echo "✅ PDO SQLite driver is available"
    echo "ℹ️ Using 'pdo_sqlite' driver"
else
    echo "❌ SQLite extensions are NOT available"
    echo "Available extensions:"
    php -m | head -20
    echo "Trying to continue anyway..."
fi

# Create SQLite database file if it doesn't exist
echo "🗄️ Setting up SQLite database..."
mkdir -p /var/www/html/database
touch /var/www/html/database/database.sqlite
chown www-data:www-data /var/www/html/database/database.sqlite
chmod 664 /var/www/html/database/database.sqlite

echo "✅ SQLite database file created"

# Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Run database seeds
echo "🌱 Running database seeds..."
php artisan db:seed --force

# Clear and cache configuration for production
echo "⚙️ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
echo "🔐 Setting proper permissions..."
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/database

echo "✅ Application setup completed!"

# Start Laravel's built-in server
echo "🚀 Starting Laravel server on port ${PORT:-8000}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
