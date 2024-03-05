#!/bin/bash

# Instalar dependencias de Composer
composer install --no-scripts --no-interaction

cp .env.example .env
php artisan key:generate
php artisan clear:cache
php artisan migrate
php artisan db:seed

# Ejecutar el comando original del contenedor (por ejemplo, iniciar el servidor web)
exec "$@"
