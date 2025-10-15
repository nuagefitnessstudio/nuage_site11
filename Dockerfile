FROM php:8.2-apache
WORKDIR /var/www/html

# 1) System deps for Composer + zips
RUN apt-get update \
 && apt-get install -y --no-install-recommends git unzip curl \
 && rm -rf /var/lib/apt/lists/*

# 2) Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
 && mv composer.phar /usr/local/bin/composer

# 3) Copy only composer files first to leverage cache
COPY nuage_site11/composer.json nuage_site11/composer.lock* /var/www/html/

# 4) Install vendors (creates /var/www/html/vendor/autoload.php)
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress

# 5) Now copy the rest of your app
COPY nuage_site11/ /var/www/html/

# 6) Apache + site setup
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
