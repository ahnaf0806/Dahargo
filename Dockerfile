FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    nginx \
    curl \
    git \
    unzip \
    nodejs \
    npm \
    libpng-dev \
    oniguruma-dev \
    icu-dev \
    libzip-dev

RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    intl \
    zip \
    gd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

RUN chown -R www-data:www-data storage bootstrap/cache

COPY docker/nginx.conf /etc/nginx/http.d/default.conf

CMD ["sh", "-c", "php-fpm & nginx -g 'daemon off;'"]
