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
RUN chmod -R 777 storage bootstrap/cache

# Instala dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Limpia caché y genera clave
RUN php artisan config:clear && php artisan cache:clear && php artisan key:generate

# Exponer el puerto de Apache
EXPOSE 8000

# Comando para iniciar Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
