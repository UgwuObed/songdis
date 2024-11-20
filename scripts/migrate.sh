#!/bin/bash
echo "Running migrations..."
echo "DB_CONNECTION: $DB_CONNECTION"
echo "DB_HOST: $DB_HOST"
echo "DB_PORT: $DB_PORT"
echo "DB_DATABASE: $DB_DATABASE"
echo "DB_USERNAME: $DB_USERNAME"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
