# ---- Base image ----
FROM php:8.2-apache

# (Optional) enable useful PHP extensions; add more if your app needs them
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Enable Apache modules (useful if you rely on .htaccess or pretty URLs)
RUN a2enmod rewrite headers

# System deps + Composer (install once, cache layers)
RUN apt-get update \
 && apt-get install -y --no-install-recommends git unzip curl \
 && rm -rf /var/lib/apt/lists/* \
 && curl -sS https://getcomposer.org/installer | php -- \
 && mv composer.phar /usr/local/bin/composer

# App directory
WORKDIR /var/www/html

# Copy composer manifests first (better layer caching)
COPY composer.json composer.lock* ./

# Install PHP deps (no dev, faster, smaller)
RUN composer install \
    --no-dev --prefer-dist --no-interaction --optimize-autoloader

# Now copy the rest of the app
COPY . /var/www/html

# Ensure Apache can read app files
RUN chown -R www-data:www-data /var/www/html

# If you need a custom DocumentRoot (e.g., /var/www/html/public), uncomment:
# RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
#  && sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/apache2.conf

# Expose HTTP
EXPOSE 80

# Apache will start by default via the base image's CMD
