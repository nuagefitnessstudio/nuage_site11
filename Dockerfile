FROM php:8.2-apache

# Work in web root
WORKDIR /var/www/html

# Copy app code (nuage_site11) into the docroot
COPY nuage_site11/ /var/www/html/

# Copy vendor/ from repo root so autoload.php exists
# (Make sure .dockerignore does NOT exclude vendor/)
COPY vendor/ /var/www/html/vendor/

# Enable Apache modules and allow .htaccess
RUN a2enmod rewrite headers && \
    printf '%s\n' \
      '<Directory /var/www/html>' \
      '  Options Indexes FollowSymLinks' \
      '  AllowOverride All' \
      '  Require all granted' \
      '</Directory>' \
      'DirectoryIndex index.php index.html' \
      > /etc/apache2/conf-available/app.conf && \
    a2enconf app

# Health check endpoint (always 200)
RUN bash -lc 'echo "<?php http_response_code(200); echo \"OK\";" > /var/www/html/healthz.php'

# Fix ownership (optional but nice)
RUN chown -R www-data:www-data /var/www/html

# Keep Apache on port 80 (simplest for DO)
EXPOSE 80
CMD ["apache2-foreground"]
