# Multi-stage build to install CodeIgniter 4 appstarter inside the image
FROM composer:2 AS builder
WORKDIR /build
RUN composer create-project codeigniter4/appstarter .

# Install JWT lib
RUN composer require firebase/php-jwt

# Copy composer files out for reference
RUN ls -la

FROM php:8.2-apache

# Apache config: point DocumentRoot to /var/www/html/public
RUN a2enmod rewrite
RUN sed -i 's#DocumentRoot /var/www/html#DocumentRoot /var/www/html/public#' /etc/apache2/sites-available/000-default.conf
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

WORKDIR /var/www/html

# Copy the built CI4 skeleton from builder
COPY --from=builder /build ./

# Overlay our app files
COPY app ./app
COPY public ./public
COPY writable ./writable
COPY spark ./spark
COPY composer.json ./composer.json

# Ensure writable permissions
RUN chown -R www-data:www-data /var/www/html/writable && chmod -R 775 /var/www/html/writable

# Install any composer deps newly referenced by overlay
RUN php -v && php -m && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"     && php composer-setup.php --install-dir=/usr/local/bin --filename=composer     && composer install --no-interaction

EXPOSE 80
