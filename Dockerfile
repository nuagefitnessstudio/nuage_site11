FROM php:8.2-apache

# System deps + Composer
RUN apt-get update \
 && apt-get install -y --no-install-recommends git unzip curl libzip-dev \
 && rm -rf /var/lib/apt/lists/* \
 && docker-php-ext-configure zip \
 && docker-php-ext-install zip mysqli pdo pdo_mysql \
 && docker-php-ext-enable mysqli pdo_mysql \
 && curl -sS https://getcomposer.org/installer | php -- \
 && mv composer.phar /usr/local/bin/composer

# Web root
WORKDIR /var/www/html

# Copy app code
COPY nuage_site11/ /var/www/html/nuage_site11/

# Copy composer manifests (from repo root) into nuage_site11
COPY composer.json composer.lock* /var/www/html/nuage_site11/

# Install vendor INSIDE nuage_site11 (matches your expected path)
RUN composer install \
    --working-dir=/var/www/html/nuage_site11 \
    --no-dev --prefer-dist --no-interaction --optimize-autoloader

# Allow .htaccess and set index order
RUN a2enmod rewrite headers && \
    printf '%s\n' \
      '<Directory /var/www/html/nuage_site11>' \
      '  Options Indexes FollowSymLinks' \
      '  AllowOverride All' \
      '  Require all granted' \
      '</Directory>' \
      'DirectoryIndex index.php index.html' \
      > /etc/apache2/conf-available/app.conf && \
    a2enconf app

# Simple health endpoint for DO
RUN bash -lc 'echo "<?php http_response_code(200); echo \"OK\";" > /var/www/html/nuage_site11/healthz.php'

# Permissions (optional)
RUN chown -R www-data:www-data /var/www/html

# Keep default Apache port for DO
EXPOSE 80
CMD ["apache2-foreground"]
