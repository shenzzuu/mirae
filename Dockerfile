FROM php:8.1-apache

# Install required PHP extensions
RUN docker-php-ext-install mysqli

# Enable Apache mod_rewrite (optional, for clean URLs)
RUN a2enmod rewrite

# Copy your project files into the container
COPY . /var/www/html/

# Expose port 80
EXPOSE 80
