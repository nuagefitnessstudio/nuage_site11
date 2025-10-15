FROM php:8.2-apache

# PHP extensions (add more if needed)
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli pdo_mysql

# Apache modules
RUN a2enmod rewrite headers

# System deps + Composer
RUN apt-get update \
 && apt-get install -y --no-install-recommends git unzip curl \
 && rm -rf /var/lib/apt/lists/* \
 && curl -sS https://getcomposer.org/installer | php -- \
 && mv composer.phar /usr/local/bin/composer

# App root
WORKDIR /var/www/html

# 1) Copy composer manifests from REPO ROOT (where your composer.json/lock live)
COPY composer.json composer.lock* ./

# 2) Install PHP deps into /var/www/html/vendor
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# 3) Copy the rest of your app (brings in nuage_site11/, .htaccess, etc.)
COPY . /var/www/html

# 4) Point Apache at your subfolder if that's where index.php lives
RUN sed -ri 's!/var/www/html!/var/www/html/nuage_site11!g' \
    /etc/apache2/sites-available/000-default.conf /etc/apache2/apache2.conf || true

# 5) Symlink vendor into nuage_site11 so require "vendor/autoload.php" works there
RUN if [ ! -e /var/www/html/nuage_site11/vendor ]; then \
      ln -s /var/www/html/vendor /var/www/html/nuage_site11/vendor ; \
    fi

# 6) Permissions
RUN chown -R www-data:www-data /var/www/html
