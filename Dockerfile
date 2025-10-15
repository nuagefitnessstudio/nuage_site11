FROM php:8.2-apache

# Enable useful modules
RUN a2enmod rewrite headers

# System deps + Composer
RUN apt-get update \
 && apt-get install -y --no-install-recommends git unzip curl libzip-dev \
 && rm -rf /var/lib/apt/lists/* \
 && docker-php-ext-configure zip \
 && docker-php-ext-install zip mysqli pdo pdo_mysql \
 && docker-php-ext-enable mysqli pdo_mysql \
 && curl -sS https://getcomposer.org/installer | php -- \
 && mv composer.phar /usr/local/bin/composer

# Serve from /var/www/html
WORKDIR /var/www/html

# If your repo has composer.json at the root, use it; otherwise create one that brings in PHPMailer
# This guarantees /var/www/html/vendor/autoload.php exists even if your repo doesn't ship vendor/
COPY composer.json /tmp/composer.json
RUN if [ -f /tmp/composer.json ]; then \
      cp /tmp/composer.json ./composer.json ; \
    else \
      printf '{ "require": { "phpmailer/phpmailer": "^6.9" } }\n' > composer.json ; \
    fi \
 && composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# Copy ONLY the app folder contents (your working pattern)
COPY nuage_site11/ /var/www/html_
