# ========= Stage 1: build PHP dependencies with Composer =========
FROM composer:2 AS vendor
WORKDIR /app

# Copy only composer manifests from the REPO ROOT
COPY composer.json composer.lock* ./

# Install production dependencies
RUN composer install \
    --no-dev --prefer-dist --no-interaction --optimize-autoloader

# ========= Stage 2: runtime (PHP 8.2 + Apache) =========
FROM php:8.2-apache

# System packages & PHP extensions commonly needed (PHPMailer needs none special, but zip is useful)
RUN apt-get update \
 && apt-get install -y --no-install-recommends libzip-dev \
 && rm -rf /var/lib/apt/lists/* \
 && docker-php-ext-configure zip \
 && docker-php-ext-install zip mysqli pdo pdo_mysql \
 && docker-php-ext-enable mysqli pdo_mysql

# Enable useful Apache modules
RUN a2enmod rewrite headers

# App root
WORKDIR /var/www/html

# Bring in vendor/ from the Composer stage to the repo root in the image
COPY --from=vendor /app/vendor /var/www/html/vendor

# Copy the rest of your repository (includes nuage_site11/)
COPY . /var/www/html

# Serve the subfolder where your app lives
ENV APACHE_DOCUMENT_ROOT=/var/www/html/nuage_site11
RUN printf '%s\n' \
  "<VirtualHost *:80>" \
  "  DocumentRoot ${APACHE_DOCUMENT_ROOT}" \
  "  <Directory ${APACHE_DOCUMENT_ROOT}>" \
  "    AllowOverride All" \
  "    Require all granted" \
  "    Options Indexes FollowSymLinks" \
  "  </Directory>" \
  "</VirtualHost>" \
  > /etc/apache2/sites-available/000-default.conf

# Health check endpoint for DigitalOcean App Platform
RUN bash -lc 'echo "<?php http_response_code(200); echo \"OK\";" > ${APACHE_DOCUMENT_ROOT}/healthz.php'

# Symlink so nuage_site11/vendor resolves to root vendor without code changes
RUN if [ ! -e /var/www/html/nuage_site11/vendor ]; then \
      ln -s /var/www/html/vendor /var/www/html/nuage_site11/vendor ; \
    fi

# Permissions
RUN chown -R www-data:www-data /var/www/html

# Expose HTTP (App Platform uses 80 by default)
EXPOSE 80
