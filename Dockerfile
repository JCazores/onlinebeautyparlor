FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
    apache2 \
    php8.1 \
    php8.1-mysql \
    libapache2-mod-php8.1 \
    && rm -rf /var/lib/apt/lists/*

RUN echo "display_errors = On" >> /etc/php/8.1/apache2/php.ini

COPY . /var/www/html/

RUN rm -f /var/www/html/index.html

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["bash", "-c", "source /etc/apache2/envvars && exec /usr/sbin/apache2 -D FOREGROUND"]