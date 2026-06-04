FROM php:8.1-apache

# Disable conflicting MPM modules, keep prefork
RUN a2dismod mpm_event mpm_worker 2>/dev/null; a2enmod mpm_prefork

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

# Default port
ENV PORT=80

# Startup script: set Apache port dari env $PORT saat runtime
RUN printf '#!/bin/bash\nsed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf\nsed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf\napache2-foreground\n' > /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

# Apache VirtualHost config
RUN printf '<VirtualHost *:80>\n    DocumentRoot /var/www/html\n    <Directory /var/www/html>\n        AllowOverride All\n        Require all granted\n    </Directory>\n</VirtualHost>\n' > /etc/apache2/sites-available/000-default.conf

CMD ["/usr/local/bin/start.sh"]
