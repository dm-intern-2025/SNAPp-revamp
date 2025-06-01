#!/bin/bash
set -e

# For debugging
echo "Received args: '$1' '$2' '$3' '$4'"

# Install dependencies
apt-get update
apt-get install -y unzip git libzip-dev
docker-php-ext-install pdo_mysql
curl -sS https://getcomposer.org/installer | php
php composer.phar install --no-dev --optimize-autoloader

# Create .env file
cp .env.example .env

# Update database settings directly with printf to avoid sed quoting issues
printf "DB_CONNECTION=mysql\n" >> .env.custom
printf "DB_HOST=%s\n" "$1" >> .env.custom
printf "DB_PORT=3306\n" >> .env.custom
printf "DB_DATABASE=%s\n" "$2" >> .env.custom
printf "DB_USERNAME=%s\n" "$3" >> .env.custom
printf "DB_PASSWORD=%s\n" "$4" >> .env.custom

# Replace old .env with our custom one
cat .env.custom >> .env

# Run Laravel commands
php artisan migrate --force