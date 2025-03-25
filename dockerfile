# Use an official PHP runtime as a parent image
FROM php:8.2-apache

# Install required system packages and dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_pgsql mysqli \
    && rm -rf /var/lib/apt/lists/*

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the application source code into the container
COPY . /var/www/html

# Copy custom Apache configuration
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Enable necessary Apache modules
RUN a2enmod rewrite headers

# Set proper file permissions (important for Laravel or similar frameworks)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 80 for incoming HTTP connections
EXPOSE 80

# Start Apache in foreground mode
CMD ["apache2-foreground"]
