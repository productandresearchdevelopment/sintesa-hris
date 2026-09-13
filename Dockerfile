FROM node:20-alpine AS frontend
WORKDIR /app

COPY package.json webpack.mix.js ./
COPY resources ./resources
COPY public ./public

RUN npm install && npm run production

FROM composer:2 AS composer-builder
WORKDIR /app

COPY composer.json ./
COPY app ./app
COPY bootstrap ./bootstrap
COPY config ./config
COPY database ./database
COPY routes ./routes

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN composer config policy.advisories.block false 2>/dev/null || true \
    && composer config audit.block false 2>/dev/null || true \
    && composer install \
        --no-dev \
        --no-interaction \
        --prefer-dist \
        --optimize-autoloader \
        --no-scripts \
        --ignore-platform-reqs \
        --no-audit

FROM php:8.2-fpm-alpine

ENV TZ=Asia/Jakarta

RUN apk add --no-cache \
    nginx \
    supervisor \
    bash \
    curl \
    git \
    unzip \
    zip \
    mariadb-client \
    tzdata

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions \
    pdo_mysql \
    gd \
    zip \
    mbstring \
    curl \
    xml \
    bcmath \
    intl \
    imagick \
    redis \
    exif \
    pcntl \
    opcache

RUN mkdir -p /run/nginx /var/log/supervisor /var/log/nginx /etc/supervisor/conf.d

COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

RUN echo "* * * * * www-data php /var/www/artisan schedule:run >> /dev/null 2>&1" > /etc/crontabs/www-data \
    && chmod 0600 /etc/crontabs/www-data

WORKDIR /var/www

COPY --chown=www-data:www-data . /var/www
COPY --chown=www-data:www-data --from=composer-builder /app/vendor /var/www/vendor
COPY --chown=www-data:www-data --from=frontend /app/public /var/www/public

RUN mkdir -p /var/www/storage/framework/sessions \
             /var/www/storage/framework/views \
             /var/www/storage/framework/cache \
             /var/www/storage/logs \
             /var/www/bootstrap/cache \
             /var/www/public/uploads \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/public/uploads \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
