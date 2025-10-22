#!/bin/bash

set -e

echo "Laravel Application Startup"

# # Ожидаем базу данных если указан хост
# if [ -n "$DB_HOST" ]; then
#     echo "Waiting for database at $DB_HOST:$DB_PORT..."
#     /usr/local/bin/wait-for-it.sh $DB_HOST:$DB_PORT -t 60 -- echo "Database is ready!"
# fi

# Устанавливаем Laravel если папка пустая
if [ -z "$(ls -A /var/www/html)" ]; then
    echo "Installing Laravel..."
    
    cd /var/www/html
    
    composer create-project laravel/laravel:^8.0 . --prefer-dist

    echo "Setting up permissions..."
    chown -R www-data:www-data /var/www/html
    chmod -R 775 /var/www/html/bootstrap/cache
    
    # Настраиваем .env для Docker
    if [ -n "$DB_HOST" ]; then
        sed -i "s/DB_HOST=127.0.0.1/DB_HOST=$DB_HOST/" .env
        sed -i "s/DB_DATABASE=laravel/DB_DATABASE=$DB_DATABASE/" .env
        sed -i "s/DB_USERNAME=root/DB_USERNAME=$DB_USERNAME/" .env
        sed -i "s/DB_PASSWORD=/DB_PASSWORD=$DB_PASSWORD/" .env
    fi
    
    php artisan key:generate
    echo "Laravel installed successfully!"
fi

# Оптимизация для production
php artisan config:cache
php artisan route:cache

