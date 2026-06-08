FROM php:8.2-cli
RUN apt-get update && apt-get install -y libmariadb-dev
RUN docker-php-ext-install mysqli pdo pdo_mysql
COPY bpms/ /app/
WORKDIR /app
EXPOSE 80
CMD ["php", "-d", "session.save_path=/tmp", "-S", "0.0.0.0:80"]