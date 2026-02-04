#!/usr/bin/env bash
# exit on error
set -o errexit

# Run composer install (Safe mode)
composer install --no-dev --optimize-autoloader --no-interaction

# Clear all caches (Fixes stale config/views issues)
php artisan optimize:clear

# Create symbolic link for storage (Skip if exists)
php artisan storage:link || true

# Run database migrations (Disabled for stability - Run manually in Shell if needed)
# php artisan migrate --force
