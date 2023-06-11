# Build stage
FROM php:8.2-fpm

# Set main params
ARG BUILD_ARGUMENT_ENV=ci
ENV ENV=$BUILD_ARGUMENT_ENV
ENV APP_HOME /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    libzip-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mysqli zip exif opcache

# Install MongoDB extension
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Install MongoDB tools
RUN apt-get update && apt-get install -y wget gnupg && \
    wget -qO - https://www.mongodb.org/static/pgp/server-5.0.asc | apt-key add - && \
    echo "deb [ arch=amd64,arm64 ] https://repo.mongodb.org/apt/debian buster/mongodb-org/5.0 main" | tee /etc/apt/sources.list.d/mongodb-org-5.0.list && \
    apt-get update && apt-get install -y mongodb-database-tools && \
    rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR $APP_HOME

# Copy source files
COPY . $APP_HOME/

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Copy only the necessary files for Composer
#COPY composer.json composer.lock ./

# Install composer dependencies
RUN composer install --optimize-autoloader --no-interaction --no-progress

# Copy nginx configuration
COPY ./.docker/ci/nginx.conf /etc/nginx/nginx.conf

# Copy PHP-FPM configuration
COPY ./.docker/ci/www.conf /usr/local/etc/php-fpm.d/www.conf

# Copy PHP configuration
COPY .docker/ci/php.ini /usr/local/etc/php/conf.d/php.ini

# Copy supervisor configuration
COPY .docker/ci/supervisor.conf /etc/supervisor/conf.d/supervisor.conf

# Fix permissions
RUN chown -R www-data:www-data var/

# Expose ports
EXPOSE 80

# Start supervisor
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]

