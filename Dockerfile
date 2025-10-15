FROM php:8.2-apache

# Needed libs + Composer inside the image (no composer.phar in repo required)
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

# Copy your app (exactly what you had)
COPY nuage_site11/ /var/www/html/

# Bring composer manifests from repo root into the web root
COPY composer.json composer.lock* /var/www/html/

# Install vendor/ so /var/www/html/vendor/autoload.php exists
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# Allow .htaccess, keep your Index order
RUN a2enmod rewrite headers && \
    printf '%s\n' \
      '<Directory /var/www/html>' \
      '  Options Indexes FollowSymLinks' \
      '  AllowOverride All' \
      '  Require all granted' \
      '</Directory>' \
      'DirectoryIndex index.php index.html' \
      > /etc/apache2/conf-available/app.conf && \
    a2enconf app

# Simple health endpoint for DO
RUN bash -lc 'echo "<?php http_response_code(200); echo \"OK\";" > /var/www/html/healthz.php'

# (Keep Apache on 80; DO expects 80 by default)
EXPOSE 80
CMD ["apache2-foreground"]
