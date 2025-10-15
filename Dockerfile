FROM php:8.2-apache
WORKDIR /var/www/html

# Tools for Composer and zip fallback
RUN apt-get update \
 && apt-get install -y --no-install-recommends git unzip curl \
 && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
 && mv composer.phar /usr/local/bin/composer

# --- CHANGE THIS IF NEEDED ---
# If composer.json lives at the REPO ROOT, change the next 2 COPY lines to:
# COPY composer.json composer.lock* /var/www/html/
# Otherwise, if it's inside nuage_site11 (as in your messages), keep:
COPY nuage_site11/composer.json nuage_site11/composer.lock* /var/www/html/
# --------------------------------

# Install PHP deps (creates vendor/)
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress || true

# Copy the rest of the app (WITHOUT local vendor/)
COPY nuage_site11/ /var/www/html/

# If vendor/ was clobbered or composer failed, add PHPMailer via zip (fallback)
RUN if [ ! -f /var/www/html/vendor/autoload.php ]; then \
      echo "Composer vendor missing, installing PHPMailer via zip fallback..." ; \
      mkdir -p /var/www/html/vendor/phpmailer && \
      curl -L -o /tmp/phpmailer.zip https://github.com/PHPMailer/PHPMailer/archive/refs/tags/v6.11.1.zip && \
      unzip -q /tmp/phpmailer.zip -d /tmp && \
      mv /tmp/PHPMailer-6.11.1 /var/www/html/vendor/phpmailer/phpmailer && \
      rm -f /tmp/phpmailer.zip && \
      printf "%s\n" "<?php" \
      "require_once __DIR__ . '/phpmailer/phpmailer/src/Exception.php';" \
      "require_once __DIR__ . '/phpmailer/phpmailer/src/PHPMailer.php';" \
      "require_once __DIR__ . '/phpmailer/phpmailer/src/SMTP.php';" \
      > /var/www/html/vendor/autoload.php ; \
    fi

# Apache on 8080 + rewrite + index priority
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
