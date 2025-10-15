FROM php:8.2-apache

# PHP extensions you likely need; add more if required
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli pdo_mysql

# Enable useful Apache modules
RUN a2enmod rewrite headers

# System deps + Composer
RUN apt-get update \
 && apt-get install -y --no-install-recommends git unzip curl \
 && rm -rf /var/lib/apt/lists/* \
 && curl -sS https://getcomposer.org/installer | php -- \
 && mv composer.phar /usr/local/bin/composer

# Work in the webroot
WORKDIR /var/www/html

# Copy Composer manifests from REPO ROOT (your actual locations)
COPY composer.json composer.lock* ./

# Install PHP deps inside the image
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# Copy the rest of your repo (includes nuage_site11/, .htaccess, etc.)
COPY . /var/www/html

# If your index.php is inside /nuage_site11, set Apache's DocumentRoot there:
RUN sed -ri 's!/var/www/html!/var/www/html/nuage_site11!g' \
    /etc/apache2/sites-available/000-default.conf /etc/apache2/apache2.conf || true

# Make sure Apache can read the files
RUN chown -R www-data:www-data /var/www/html

# ... previous steps ...
WORKDIR /var/www/html
COPY composer.json composer.lock* ./
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader \
 && ln -s /var/www/html/vendor /var/www/html/nuage_site11/vendor