FROM php:8.1-cli

# Install ekstensi PHP yang dibutuhkan
RUN docker-php-ext-install pdo pdo_mysql

# Enable semua env variables di PHP
RUN echo "variables_order = EGPCS" > /usr/local/etc/php/conf.d/env.ini

# Set working directory
WORKDIR /var/www/html

# Copy semua file project
COPY . .

# Database environment variables (Aiven)
ENV DB_HOST=sigma-db-faizakmall-d.j.aivencloud.com
ENV DB_PORT=22892
ENV DB_NAME=defaultdb
ENV DB_USER=avnadmin
ENV DB_PASS=AVNS_m62ILEHcEWIeqFEcHyZ

# Default port (Railway override via env)
ENV PORT=80

EXPOSE 80

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT} -t . router.php"]
