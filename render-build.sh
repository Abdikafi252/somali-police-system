#!/usr/bin/env bash
# exit on error
set -o errexit

# Run composer install with maximum safety
composer install --no-dev --optimize-autoloader --no-interaction --no-scripts
