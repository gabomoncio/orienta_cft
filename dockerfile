FROM php:8.2-apache

# Instalar las dependencias y los drivers de PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Copiar los archivos de tu repositorio al servidor
COPY . /var/www/html/

# Configurar permisos básicos
RUN chown -R www-data:www-data /var/www/html
