FROM php:8.2-apache

# 1) System deps + Composer (and allow running as root)
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN apt-get update \
 && apt-get install -y --no-install-recommends git unzip curl libzip-dev \
 && rm -rf /var/lib/apt/lists/* \
 && curl -sS https://getcomposer.org/installer | php -- \
 && mv composer.phar /usr/local/bin/composer

# 2) PHP extensions required by many libs and PHPMailer
RUN docker-php-ext-configure zip \
 && docker-php-ext-install zip mysqli pdo pdo_mysql \
 && docker-php-ext-enable mysqli pdo_mysql

# 3) Useful Apache modules
RUN a2enmod rewrite headers

# 4) App root & Composer install (vendor at repo root)
WORKDIR /var/www/html
COPY composer.json composer.lock* ./
# If lock causes platform issues, swap next line for:
# RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader --ignore-platform-reqs
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# 5) Copy the rest of your code
COPY . /var/www/html

# 6) Serve the subfolder (your code lives in nuage_site11/)
ENV APACHE_DOCUMENT_ROOT=/var/www/html/nuage_site11
RUN sed -ri "s!DocumentRoot /var/www/html!DocumentRoot ${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/000-default.conf \
 && sed -ri "s!<Directory /var/www/>!<Directory ${APACHE_DOCUMENT_ROOT}>!g" /etc/apache2/apache2.conf || true

# 7) Make vendor available inside nuage_site11 without changing your PHP
RUN if [ ! -e /var/www/html/nuage_site11/vendor ]; then \
      ln -s /var/www/html/vendor /var/www/html/nuage_site11/vendor ; \
    fi

# 8) Permissions
RUN chown -R www-data:www-data /var/www/html
