FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Copy ONLY the contents of nuage_site11 into the docroot
COPY nuage_site11/ /var/www/html/

# --- NEW: copy vendor so autoload.php exists ---
COPY vendor/ /var/www/html/vendor/

# --- NEW: make code inside nuage_site11 find vendor/autoload.php ---
RUN if [ ! -e /var/www/html/nuage_site11/vendor ]; then \
      ln -s /var/www/html/vendor /var/www/html/nuage_site11/vendor ; \
    fi

# Fix ownership and permissions
RUN chown -R www-data:www-data /var/www/html \
 && find /var/www/html -type d -exec chmod 755 {} \; \
 && find /var/www/html -type f -exec chmod 644 {} \;

# Switch Apache to port 8080 for App Platform
RUN sed -ri 's/Listen 80/Listen 8080/g' /etc/apache2/ports.conf \
 && sed -ri 's/:80>/:8080>/g' /etc/apache2/sites-available/000-default.conf

# Enable URL rewriting and make sure index.php loads first
RUN a2enmod rewrite \
 && printf '%s\n' \
   '<Directory /var/www/html>' \
   '  Options Indexes FollowSymLinks' \
   '  AllowOverride All' \
   '  Require all granted' \
   '</Directory>' \
   'DirectoryIndex index.php index.html' \
   > /etc/apache2/conf-available/app.conf \
 && a2enconf app

# --- NEW: quiet the ServerName warning ---
RUN sh -lc 'echo "ServerName localhost" > /etc/apache2/conf-available/servername.conf' \
 && a2enconf servername

# (Optional) simple health endpoint if you want to point DO’s check here
RUN sh -lc 'echo "<?php http_response_code(200); echo \"OK\";" > /var/www/html/healthz.php'

EXPOSE 8080
CMD ["apache2-foreground"]
