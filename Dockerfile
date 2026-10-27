# syntax=docker/dockerfile:1
# =====================================================================
# Dockerfile - Barbershop (Laravel 12 + MySQL)
# Multi-stage build: vendor -> frontend -> runtime
# Runtime: PHP 8.4 + Apache (satu proses, tanpa Supervisor)
# =====================================================================


# ---------------------------------------------------------------------
# Tahap 1 - vendor
# Memasang library PHP (Laravel) dengan Composer.
# --no-dev            : paket pengembangan (Pest, Pint, Sail, dst.) tidak ikut
# --no-scripts        : script artisan dijalankan nanti saat container mulai
# --ignore-platform-reqs : ekstensi PHP dicek pada tahap runtime, bukan di sini
# ---------------------------------------------------------------------
FROM composer:2 AS vendor
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --no-interaction \
        --no-progress \
        --no-scripts \
        --no-autoloader \
        --prefer-dist \
        --ignore-platform-reqs

COPY . .
RUN composer dump-autoload \
        --no-dev \
        --no-scripts \
        --optimize \
        --classmap-authoritative


# ---------------------------------------------------------------------
# Tahap 2 - frontend
# Membangun CSS/JS (Vite + Tailwind) menjadi public/build.
# Node.js hanya dipakai di tahap ini dan tidak masuk ke image akhir.
# ---------------------------------------------------------------------
FROM node:22-alpine AS frontend
WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY vite.config.js postcss.config.js tailwind.config.js ./
COPY resources ./resources

# tailwind.config.js ikut memindai view pagination milik Laravel di vendor/
COPY --from=vendor /app/vendor/laravel/framework/src/Illuminate/Pagination/resources/views \
     ./vendor/laravel/framework/src/Illuminate/Pagination/resources/views

RUN npm run build


# ---------------------------------------------------------------------
# Tahap 3 - runtime
# Image akhir: PHP 8.4 + Apache + source aplikasi.
# (composer.lock memakai Symfony 8 yang membutuhkan PHP >= 8.4.1)
# ---------------------------------------------------------------------
FROM php:8.4-apache AS runtime

# Apache melayani folder public/ saja, sehingga source code dan file .env
# tidak dapat diakses langsung dari browser.
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN docker-php-ext-install -j"$(nproc)" pdo_mysql opcache \
    && a2enmod rewrite \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && printf 'ServerName localhost\nServerTokens Prod\nServerSignature Off\n' \
         > /etc/apache2/conf-available/barbershop.conf \
    && a2enconf barbershop \
    && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /var/www/html

# Source aplikasi + hasil dari dua tahap sebelumnya
COPY . .
COPY --from=vendor   /app/vendor       ./vendor
COPY --from=frontend /app/public/build ./public/build

# Direktori yang harus dapat ditulis oleh Apache (www-data)
RUN mkdir -p storage/app/public \
             storage/framework/cache/data \
             storage/framework/sessions \
             storage/framework/views \
             storage/logs \
             bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwX storage bootstrap/cache

# Script persiapan saat container dimulai.
# sed menghapus karakter CRLF (Windows) agar script tetap bisa dijalankan di Linux.
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["apache2-foreground"]
