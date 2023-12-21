# Usar la imagen de PHP con Alpine
FROM php:8.2.13-fpm-alpine3.19

# Instalar dependencias adicionales requeridas para tu proyecto
RUN apk update && apk add --no-cache \
    zip \
    unzip \
    dos2unix \
    supervisor \
    libpng-dev \
    libzip-dev \
    freetype-dev \
    libjpeg-turbo-dev

# Compilar extensiones de PHP
RUN docker-php-ext-install \
    gd \
    pcntl \
    bcmath \
    mysqli \
    pdo_mysql

# Configurar extensiones de PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Instalar NGINX desde los repositorios de Alpine
RUN apk add --no-cache nginx

# Opcional: Instalar paquetes adicionales de PECL si se requieren
# RUN pecl install zip && docker-php-ext-enable zip \
#     && pecl install igbinary && docker-php-ext-enable igbinary \
#     && yes | pecl install redis && docker-php-ext-enable redis

# Configurar logs de NGINX para que se dirijan a stdout y stderr
RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log

# Copiar la configuración de Supervisor (si se usa)
COPY ./config/supervisord.conf /etc/supervisord.conf

# Exponer el puerto en el que NGINX escuchará
EXPOSE 80

# Comando para iniciar Supervisor (ajustar según sea necesario)
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisord.conf"]
