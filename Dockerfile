FROM php:8.3-cli-alpine

RUN apk add --no-cache \
    sqlite-dev \
    libzip-dev \
    unzip \
    php83-pecl-xdebug \
    php83-pdo_sqlite && \
    echo -e "zend_extension=/usr/lib/php83/modules/xdebug.so\nxdebug.mode=coverage,develop" > $PHP_INI_DIR/conf.d/xdebug.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /app
WORKDIR /app

RUN composer install;

EXPOSE 8001

CMD ["php", "-S", "0.0.0.0:8001", "-t", "/app/public"]
