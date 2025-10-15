# ===== Stage 1: build PHP deps with Composer =====
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock* ./
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# ===== Stage 2: runtime (PHP 8.2 + Apache) =====
FROM php:8.2-apache

# System packages & PHP extensions
RUN apt-get update \
 && apt-get install -y --no-install-recommends libzip-dev \
 && rm -rf /var/lib/apt/lists/* \
 && docker-php-ext-configure zip \
 && docker-php-ext-install zip mysqli pdo pdo_mysql \
 && docker-php-ext-enable mysqli pdo_mysql

# Apache modules
RUN a2enmod rewrite headers

# App root
WORKDIR /var/www/html

# Bring in vendor/ produced by Composer stage (at repo root in image)
COPY --from=vendor /app/vendor /var/www/html/vendor

# Copy entire repo (includes nuage_site11/)
COPY . /var/www/html

# Serve your subfolder as web root (change if you serve from root)
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

# Health check endpoint for DigitalOcean
RUN bash -lc 'echo "<?php http_response_code(200); echo \"OK\";" > ${APACHE_DOCUMENT_ROOT}/healthz.php'

# Symlink so nuage_site11/vendor resolves to root vendor (no code changes needed)
RUN if [ ! -e /var/www/html/nuage_site11/vendor ]; then \
      ln -s /var/www/html/vendor /var/www/html/nuage_site11/vendor ; \
    fi

# Permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
