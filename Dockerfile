# Usa una imagen de PHP con Apache
FROM php:8.2-apache

# Instala dependencias necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_pgsql

# Instala Composer de forma segura
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Copia el código del proyecto
COPY . /var/www/html
WORKDIR /var/www/html

# Configurar permisos
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# Instala dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Limpiar caché, regenerar clave y recargar variables de entorno
RUN rm -f .env && cp .env.example .env && php artisan config:clear && php artisan cache:clear && php artisan key:generate

EXPOSE $PORT
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=${PORT}"]
