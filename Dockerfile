FROM node:20-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY resources ./resources
COPY vite.config.js ./
COPY tailwind.config.js postcss.config.js ./
RUN npm run build

FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring bcmath zip opcache \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

# Laravel uses public/.htaccess for clean routes such as /login and /dashboard.
# Debian Apache disables .htaccess overrides by default, so explicitly enable them.
RUN printf '%s\n' \
    '<Directory /var/www/html/public>' \
    '    Options -Indexes +FollowSymLinks' \
    '    AllowOverride All' \
    '    Require all granted' \
    '</Directory>' \
    > /etc/apache2/conf-available/laravel-public.conf \
    && a2enconf laravel-public

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . .
COPY --from=frontend /app/public/build ./public/build

RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist
RUN chown -R www-data:www-data storage bootstrap/cache
EXPOSE 80
CMD ["sh", "-c", "php artisan migrate --force && php artisan db:seed --force; php artisan storage:link || true; php artisan config:cache; php artisan view:cache; exec apache2-foreground"]
