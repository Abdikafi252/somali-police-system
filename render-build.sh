#!/usr/bin/env bash
# Exit on error
set -o errexit

# Install PHP dependencies
composer install --no-dev --optimize-autoloader --no-interaction

# Force remove public/storage to prevent "link exists" errors
rm -rf public/storage

# Create storage link
php artisan storage:link

echo "Build finished!"
