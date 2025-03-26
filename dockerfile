# Use an official PHP runtime as a parent image
FROM php:8.2-apache

# Install required system packages and dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_pgsql pdo_mysql mysqli \
    && rm -rf /var/lib/apt/lists/*

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the application source code into the container
COPY . /var/www/html

# Ensure index.php is explicitly copied (prevents missing DirectoryIndex errors)
COPY index.php /var/www/html/

# Copy custom Apache configuration (adjust path if necessary)
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Enable necessary Apache modules (rewrite and headers)
RUN a2enmod rewrite headers

# Set proper permissions for Apache to serve files correctly
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Fix potential permission issues for logs and temp directories
RUN mkdir -p /var/log/apache2 /var/www/html/tmp \
    && chown -R www-data:www-data /var/log/apache2 /var/www/html/tmp

# Install Composer for dependency management (if needed)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install dependencies using Composer (uncomment if using PHP frameworks like Laravel)
# RUN composer install --no-dev --optimize-autoloader

# Expose port 80 for incoming HTTP connections
EXPOSE 80

# Start Apache in foreground mode
CMD ["apache2-foreground"]
