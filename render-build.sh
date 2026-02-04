#!/usr/bin/env bash
# exit on error
set -o errexit

# Run composer install with ULTRA safety (No scripts, No interaction, No version check)
composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --ignore-platform-reqs

# Run database migrationsn
# php artisan migrate --force

# php artisan config:cache
# php artisan route:cache
# php artisan view:cache

# Create symbolic link for storage
php artisan storage:link
