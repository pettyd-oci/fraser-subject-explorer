# Use the official PHP image with Apache
FROM php:8.3-apache

# Enable common extensions (optional)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy project files into the container
COPY ./src /var/www/html/

# Expose port 80
EXPOSE 80