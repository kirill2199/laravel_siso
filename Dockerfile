FROM php:8.1-fpm

# Установка системных зависимостей
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    jq \
    unzip \
    libzip-dev \
    libicu-dev \
    default-mysql-client \
    netcat-openbsd \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        intl \
        zip \
        sockets

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


# Копируем скрипты
COPY docker/entrypoint.sh /usr/local/bin/
COPY docker/wait-for-it.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh /usr/local/bin/wait-for-it.sh

# Создание рабочей директории
WORKDIR /var/www/html

# Клонирование репозитория прямо в рабочую директорию
RUN git clone https://github.com/kirill2199/laravel_siso.git . \
    && git config --global --add safe.directory /var/www/html

# Установка зависимостей Composer
RUN composer install --no-dev --optimize-autoloader

# Настройка прав доступа
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Копирование конфигурационных файлов
COPY .env.example .env

# Генерация ключа приложения
RUN php artisan key:generate

EXPOSE 9000

CMD ["php-fpm"]