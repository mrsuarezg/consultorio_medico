#!/bin/sh
set -e

# Ejecutar el comando supervisord en segundo plano
/usr/bin/supervisord -n -c /etc/supervisord.conf &

# Ejecutar el comando php artisan serve en segundo plano
php artisan serve --host=0.0.0.0 --port=8080
