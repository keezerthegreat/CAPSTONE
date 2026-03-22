FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache caddy nodejs npm sqlite zip unzip

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_sqlite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

# Install dependencies and build assets
RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN npm ci && npm run build

# Laravel setup
RUN php artisan migrate --force \
    && php artisan storage:link --force \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Write Caddyfile — uses {$PORT} which Caddy reads from env at runtime
RUN echo ':{$PORT}' > /etc/caddy/Caddyfile \
    && echo '{' >> /etc/caddy/Caddyfile \
    && echo '    root * /app/public' >> /etc/caddy/Caddyfile \
    && echo '    encode gzip' >> /etc/caddy/Caddyfile \
    && echo '    php_fastcgi 127.0.0.1:9000' >> /etc/caddy/Caddyfile \
    && echo '    file_server' >> /etc/caddy/Caddyfile \
    && echo '}' >> /etc/caddy/Caddyfile

# Start script
RUN printf '#!/bin/sh\nphp-fpm -D\nexec caddy run --config /etc/caddy/Caddyfile --adapter caddyfile\n' > /start.sh \
    && chmod +x /start.sh

EXPOSE 8080

CMD ["/start.sh"]
