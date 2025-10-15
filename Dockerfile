FROM php:8.2-apache

# deps + Composer
RUN apt-get update \
 && apt-get install -y --no-install-recommends git unzip curl libzip-dev \
 && rm -rf /var/lib/apt/lists/* \
 && docker-php-ext-configure zip \
 && docker-php-ext-install zip mysqli pdo pdo_mysql \
 && docker-php-ext-enable mysqli pdo_mysql \
 && curl -sS https://getcomposer.org/installer | php -- \
 && mv composer.phar /usr/local/bin/composer

WORKDIR /var/www/html

# Copy app first
COPY nuage_site11/ /var/www/html/

# If you keep composer.json at repo root, copy it here; if not, generate a minimal one (PHPMailer)
COPY composer.json /tmp/composer.json
RUN if [ -f /tmp/composer.json ]; then \
      cp /tmp/composer.json /var/www/html/composer.json ; \
    else \
      printf '{ "require": { "phpmailer/phpmailer": "^6.9" } }\n' > /var/www/html/composer.json ; \
    fi \
 && composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader --working-dir=/var/www/html

# hard check
RUN test -f /var/www/html/vendor/autoload.php || (echo "❌ Composer failed: no autoload.php" && exit 1)

# Apache (same as before)
RUN sed -ri 's/Listen 80/Listen 8080/g' /etc/apache2/ports.conf \
 && sed -ri 's/:80>/:8080>/g' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite headers \
 && printf '%s\n' \
   '<Directory /var/www/html>' \
   '  Options Indexes FollowSymLinks' \
   '  AllowOverride All' \
   '  Require all granted' \
   '</Directory>' \
   'DirectoryIndex index.php index.html' \
   > /etc/apache2/conf-available/app.conf \
 && a2enconf app
RUN sh -lc 'echo "ServerName localhost" > /etc/apache2/conf-available/servername.conf' && a2enconf servername

EXPOSE 8080
CMD ["apache2-foreground"]
