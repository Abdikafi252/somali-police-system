#!/usr/bin/env bash
# exit on error
set -o errexit

# Run composer install
composer install --no-dev --optimize-autoloader

# Create symbolic link for storage
php artisan storage:link

# Run database migrations (Disabled for stability - Run manually in Shell if needed)
# php artisan migrate --force
