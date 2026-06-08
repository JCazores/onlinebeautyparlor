FROM php:8.2-apache
RUN apt-get update && apt-get install -y libmariadb-dev
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN a2enmod rewrite
RUN rm -f /etc/apache2/mods-enabled/mpm_event.conf \
    /etc/apache2/mods-enabled/mpm_event.load
COPY bpms/ /var/www/html/
RUN rm -f /var/www/html/index.html
RUN chown -R www-data:www-data /var/www/html
EXPOSE 80
CMD ["apache2-foreground"]