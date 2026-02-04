#!/usr/bin/env bash
# Exit on error
set -o errexit

composer install --no-dev --optimize-autoloader --no-interaction
php artisan storage:link || true
echo "Build Completed!"
