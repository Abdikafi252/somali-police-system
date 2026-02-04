#!/usr/bin/env bash
# exit on error
set -o errexit

# Run composer install with NUCLEAR safety (Ignore PHP version checks)
composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --ignore-platform-reqs
