#!/usr/bin/env bash
# Exit on error
set -o errexit

echo "Deploying..."

# Install PHP dependencies
composer install --no-dev --optimize-autoloader --no-interaction

# Create storage link
php artisan storage:link

echo "Build finished!"
