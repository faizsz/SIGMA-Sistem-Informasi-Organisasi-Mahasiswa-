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

# Startup: tulis env vars ke .env file lalu start PHP server
CMD ["sh", "-c", "echo \"DB_HOST=${DB_HOST}\nDB_PORT=${DB_PORT}\nDB_NAME=${DB_NAME}\nDB_USER=${DB_USER}\nDB_PASS=${DB_PASS}\" > /var/www/html/.env && php -S 0.0.0.0:${PORT} -t . router.php"]
