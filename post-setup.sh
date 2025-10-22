#!/bin/bash

echo "Running post-setup script..."

# Даем контейнерам время запуститься
sleep 10

# Генерируем ключ приложения
docker-compose exec app php artisan key:generate

# Настраиваем права
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data /var/www

# Запускаем миграции
docker-compose exec app php artisan migrate

# Устанавливаем симлинк для storage
docker-compose exec app php artisan storage:link

echo "Laravel setup completed!"
echo "Application is available at: http://localhost:8000"