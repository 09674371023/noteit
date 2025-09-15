# Use official PHP + Apache image
FROM php:8.2-apache

# Enable Apache mod_rewrite (for clean URLs if you need it)
RUN a2enmod rewrite

# Copy project files into container
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Expose port 80 (default Apache)
EXPOSE 80
