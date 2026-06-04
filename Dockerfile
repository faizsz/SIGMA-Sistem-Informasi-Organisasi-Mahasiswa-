FROM php:8.1-cli

# Install ekstensi PHP yang dibutuhkan
RUN docker-php-ext-install pdo pdo_mysql

# Enable semua env variables di PHP
RUN echo "variables_order = EGPCS" > /usr/local/etc/php/conf.d/env.ini

# Set working directory
WORKDIR /var/www/html

# Copy semua file project
COPY . .

# Default port (Railway override via env)
ENV PORT=80

EXPOSE 80

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT} -t . router.php"]
