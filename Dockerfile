FROM php:8.2-apache

# Copy site
COPY . /var/www/html/

# App Platform expects 8080
RUN sed -ri 's/Listen 80/Listen 8080/g' /etc/apache2/ports.conf \
 && sed -ri 's/:80>/:8080>/g' /etc/apache2/sites-available/000-default.conf

EXPOSE 8080
CMD ["apache2-foreground"]
