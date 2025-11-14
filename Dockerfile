# Etapa de build con Composer
FROM composer:2 AS vendor
WORKDIR /application

# Instala las libs de sistema para intl y gd en Alpine
#RUN apk add --no-cache \
#      icu-dev \
#      libpng-dev \
#      libjpeg-turbo-dev \
#      freetype-dev \
#      zlib-dev \
#      libzip-dev \
#    # Configura y compila intl y gd \
#    && docker-php-ext-configure gd \
#         --with-freetype=/usr/include/ \
#         --with-jpeg=/usr/include/ \
#    && docker-php-ext-install \
#         intl \
#         gd \
#         zip

# Solo copiamos composer.json y composer.lock para aprovechar cache
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-scripts

# Etapa final: FrankenPHP + vendor ya instalado
FROM dunglas/frankenphp:php8.4-alpine AS production

ENV SERVER_NAME=lti.dealernode.net
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /application

# (Repite instalación de extensiones para runtime)
RUN install-php-extensions \
      pdo_pgsql
#      intl \
#      gd \
#      zip

# Copiamos el vendor generado
COPY --from=vendor /application/vendor /application/vendor

# Copiamos el resto de la aplicación
COPY . .

# Ajusta permisos
RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# Storage link para Laravel
RUN php artisan storage:link

# Cacheamos config, rutas y vistas
RUN php artisan config:cache \
 && php artisan route:cache \
 && php artisan view:cache \
 && php artisan optimize
# && php artisan filament:optimize

