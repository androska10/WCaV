FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    curl \
    git \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN mkdir -p /var/www/logs

EXPOSE 8000

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8000} index.php"]