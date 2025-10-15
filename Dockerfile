FROM php:8.2-apache
WORKDIR /var/www/html

RUN apt-get update \
 && apt-get install -y --no-install-recommends git unzip curl \
 && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- \
 && mv composer.phar /usr/local/bin/composer

# Copy ROOT composer files and install vendors
COPY composer.json composer.lock* /var/www/html/
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress

# Now copy the app (WITHOUT local vendor/)
COPY nuage_site11/ /var/www/html/

# Safety: if vendor vanished, reinstall
RUN if [ ! -f /var/www/html/vendor/autoload.php ]; then \
      rm -rf /var/www/html/vendor && composer install --no-dev --prefer-dist --no-interaction --no-progress ; \
    fi

# (rest the same…)
RUN sed -ri 's/Listen 80/Listen 8080/g' /etc/apache2/ports.conf \
 && sed -ri 's/:80>/:8080>/g' /etc/apache2/sites-available/000-default.conf \
 && a2enmod rewrite \
 && printf '%s\n' \
   '<Directory /var/www/html>' \
   '  Options Indexes FollowSymLinks' \
   '  AllowOverride All' \
   '  Require all granted' \
   '</Directory>' \
   'DirectoryIndex index.php index.html' \
   > /etc/apache2/conf-available/app.conf \
 && a2enconf app

EXPOSE 8080
CMD ["apache2-foreground"]
