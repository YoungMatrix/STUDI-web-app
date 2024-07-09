# File verified

# Use the official PHP image with Apache and PHP 8.2
FROM php:8.2-apache

# Install libzip-dev and PHP zip extension
RUN apt-get update \
    && apt-get install -y libzip-dev \
    && docker-php-ext-install zip

# Install PDO extension
RUN docker-php-ext-install pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application files into the image directory
COPY . /var/www/html/

# Adjust permissions for the application directory
RUN chown -R www-data:www-data /var/www/html/app \
    && chmod -R 555 /var/www/html/app

# Adjust permissions specifically for the .env file
RUN chown www-data:www-data /var/www/html/app/.env \
    && chmod 500 /var/www/html/app/.env

# Install dependencies via Composer (excluding development dependencies)
RUN composer install

# Expose port 80
EXPOSE 80

# Set ServerName for Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Start the Apache server
CMD ["apache2-foreground"]
