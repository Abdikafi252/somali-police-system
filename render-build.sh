#!/usr/bin/env bash
# exit on error
set -o errexit

# Run composer install
composer install --no-dev --optimize-autoloader

# Create symbolic link for storage (Only if not exists)
if [ ! -L public/storage ]; then
    php artisan storage:link
fi

# Run database migrations (Disabled for stability - Run manually in Shell if needed)
# php artisan migrate --force
