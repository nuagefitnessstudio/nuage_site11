FROM php:8.2-apache
WORKDIR /var/www/html

# Tools for composer & zips
RUN apt-get update \
 && apt-get install -y --no-install-recommends git unzip curl \
 && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
 && mv composer.phar /usr/local/bin/composer

# --- IMPORTANT: copy composer files from nuage_site11 and install vendors ---
COPY nuage_site11/composer.json nuage_site11/composer.lock* /var/www/html/
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress
# ---------------------------------------------------------------------------

# Now copy the rest of the app (WITHOUT local vendor/)
COPY nuage_site11/ /var/www/html/

# Safety: if vendor got clobbered by the copy above, reinstall
RUN if [ ! -f /var/www/html/vendor/autoload.php ]; then \
      rm -rf /var/www/html/vendor && composer install --no-dev --prefer-dist --no-interaction --no-progress ; \
    fi

# Apache 8080 + rewrite + index priority
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
