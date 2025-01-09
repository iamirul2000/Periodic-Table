FROM php:8.1-apache

WORKDIR /var/www/html

ARG WWWGROUP

RUN apt-get update && \
    apt-get install -y \
    git \
    libzip-dev \
    libpng-dev \
    libicu-dev \
    libpq-dev \
    libmagickwand-dev 

RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
RUN docker-php-ext-install pdo_mysql zip exif pcntl bcmath gd && \ 
    a2enmod rewrite && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www/html
RUN rm -rf vendor && \ 
    rm -rf node_modules && \
    rm -rf public/storage/ && \
    rm -rf public/hot && \
    rm -rf public/vendor

RUN composer install --prefer-dist --no-suggest && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html/storage

EXPOSE 80
CMD ["apache2-foreground"]
