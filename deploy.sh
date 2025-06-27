#!/bin/bash

# Laravel Application Deployment Script
set -e

echo "🚀 Starting Laravel Application Deployment..."

# Check if .env file exists
if [ ! -f .env ]; then
    echo "❌ Error: .env file not found!"
    echo "Please create a .env file with your production configuration."
    exit 1
fi

# Load environment variables
source .env

# Check required environment variables
required_vars=("DB_DATABASE" "DB_USERNAME" "DB_PASSWORD" "ONESIGNAL_APP_ID" "ONESIGNAL_REST_API_KEY")
for var in "${required_vars[@]}"; do
    if [ -z "${!var}" ]; then
        echo "❌ Error: $var is not set in .env file!"
        exit 1
    fi
done

echo "✅ Environment variables validated"

# Stop existing containers
echo "🛑 Stopping existing containers..."
docker-compose -f docker-compose.prod.yml down --remove-orphans

# Build and start containers
echo "🔨 Building and starting containers..."
docker-compose -f docker-compose.prod.yml up --build -d

# Wait for MySQL to be ready
echo "⏳ Waiting for MySQL to be ready..."
until docker-compose -f docker-compose.prod.yml exec -T mysql mysqladmin ping -h"localhost" --silent; do
    echo "Waiting for MySQL..."
    sleep 2
done

# Run database migrations
echo "🗄️ Running database migrations..."
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# Clear and cache configuration
echo "⚙️ Optimizing application..."
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache

# Set proper permissions
echo "🔐 Setting proper permissions..."
docker-compose -f docker-compose.prod.yml exec app chown -R www-data:www-data /var/www/html/storage
docker-compose -f docker-compose.prod.yml exec app chown -R www-data:www-data /var/www/html/bootstrap/cache

echo "✅ Deployment completed successfully!"
echo "🌐 Your application is now running at: http://localhost"
echo "📊 To view logs: docker-compose -f docker-compose.prod.yml logs -f"
echo "🛑 To stop: docker-compose -f docker-compose.prod.yml down"
