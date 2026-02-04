#!/usr/bin/env bash
# exit on error
set -o errexit

# Run composer install with NUCLEAR safety (Ignore PHP version checks)
composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --ignore-platform-reqs

# Run database migrations
# Skip cache commands during build (Environment vars may not be ready)
# php artisan config:cache
# php artisan route:cache
# php artisan view:cache

# Create symbolic link for storage
php artisan storage:link
