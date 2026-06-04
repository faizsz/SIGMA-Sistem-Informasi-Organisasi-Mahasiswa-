FROM php:8.1-apache

# Enable Apache mod_rewrite dan headers
RUN a2enmod rewrite headers

# Install ekstensi PHP yang dibutuhkan
RUN docker-php-ext-install pdo pdo_mysql

# Set working directory
WORKDIR /var/www/html

# Copy semua file project
COPY . .

# Set permission
RUN chown -R www-data:www-data /var/www/html

# Default port (Railway akan override via env)
ENV PORT=80

# Script startup: ganti port Apache sesuai $PORT saat runtime
RUN echo '#!/bin/bash\n\
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf\n\
sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf\n\
apache2-foreground' > /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

# Konfigurasi Apache DocumentRoot
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html\n\
    <Directory /var/www/html>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]
