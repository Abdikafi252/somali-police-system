#!/usr/bin/env bash
# exit on error
set -o errexit

# Run composer install
composer install --no-dev --optimize-autoloader

# Run database migrations
php artisan migrate --force

# php artisan config:cache
# php artisan route:cache
# php artisan view:cache

# Create symbolic link for storage
php artisan storage:link
