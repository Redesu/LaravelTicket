#!/bin/bash
cd /var/www/laravelticket/LaravelTicket

echo "Pulling latest changes..."
git pull

echo "Installing dependencies..."
composer install --optimize-autoloader --no-dev

echo "Running migrations..."
php artisan migrate --force

echo "Clearing cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Deployment complete!"