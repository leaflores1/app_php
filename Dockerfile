# Usa PHP 8.3 con Apache
FROM php:8.3-apache

# Ajustes
ENV COMPOSER_ALLOW_SUPERUSER=1

# Actualiza apt-get e instala las librerías necesarias
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Habilita rewrite en Apache
RUN a2enmod rewrite

# Establece la carpeta de trabajo
WORKDIR /var/www/html

# Crea los directorios necesarios y asigna permisos/owner
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache && \
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copia todo el código fuente al contenedor (incluyendo update_schema.php, config/, src/, etc.)
COPY . /var/www/html/

# Instala las dependencias de Composer
RUN composer install --optimize-autoloader

# Abre el puerto 80
EXPOSE 80

# Ejecuta Apache
CMD ["apache2-foreground"]