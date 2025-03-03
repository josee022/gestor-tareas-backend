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

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copia el código del proyecto
COPY . /var/www/html
WORKDIR /var/www/html

# Configurar la conexión a PostgreSQL en el entorno
ENV DB_CONNECTION=pgsql
ENV DB_HOST=dpg-cv26alumphs739kuhoh0-a
ENV DB_PORT=5432
ENV DB_DATABASE=gestor_tareas_98pb
ENV DB_USERNAME=gestor_tareas_98pb_user
ENV DB_PASSWORD=Q2ZzWlNOvDyQ4JtAscJgRwzThXWtXGyZ

# Instala dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader
RUN php artisan config:clear && php artisan cache:clear && php artisan key:generate

# Configura permisos
RUN chmod -R 777 storage bootstrap/cache

# Exponer el puerto de Apache
EXPOSE 8000

# Comando para iniciar Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
