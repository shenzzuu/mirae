FROM php:8.1-apache

# Install required PHP extensions
RUN docker-php-ext-install mysqli

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy your project files into the container
COPY . /var/www/html/

# Create and set proper permissions for the uploads folder
RUN mkdir -p /var/www/html/uploads \
    && chmod -R 777 /var/www/html/uploads

# Expose port 80
EXPOSE 80
