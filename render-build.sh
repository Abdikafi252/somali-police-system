#!/usr/bin/env bash
# exit on error
set -o errexit

# Run composer install with NUCLEAR safety (Ignore PHP version checks)
composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --ignore-platform-reqs

# Run database migrations (Try to run, but don't fail build if DB is offline)
php artisan migrate --force || true
# php artisan config:cache
# php artisan route:cache
# php artisan view:cache

# Create symbolic link for storage
php artisan storage:link
