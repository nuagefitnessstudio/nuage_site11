FROM php:8.2-apache

# Keep defaults simple; enable useful modules
RUN a2enmod rewrite headers

# Web root
WORKDIR /var/www/html

# Copy your app code (the stuff inside the subfolder)
COPY nuage_site11/ /var/www/html/

# Copy your existing vendor/ from the repo root into the container
# This ensures /var/www/html/vendor/autoload.php exists
COPY vendor/ /var/www/html/vendor/

# Also place vendor/ inside the app folder so __DIR__.'/vendor/autoload.php' works anywhere
RUN if [ ! -d /var/www/html/nuage_site11/vendor ]; then \
      cp -a /var/www/html/vendor /var/www/html/nuage_site11/ ; \
    fi

# Allow .htaccess and set index order
RUN printf '%s\n' \
  '<Directory /var/www/html>' \
  '  Options Indexes FollowSymLinks' \
  '  AllowOverride All' \
  '  Require all granted' \
  '</Directory>' \
  'DirectoryIndex index.php index.html' \
  > /etc/apache2/conf-available/app.conf && a2enconf app

# Simple health endpoint for DigitalOcean
RUN bash -lc 'echo "<?php http_response_code(200); echo \"OK\";" > /var/www/html/healthz.php'

# Permissions (optional)
RUN chown -R www-data:www-data /var/www/html

# Keep Apache on the default port DO expects
EXPOSE 80
CMD ["apache2-foreground"]
