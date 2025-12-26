FROM php:8.3-fpm

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libicu-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    libmongoc-1.0-0 \
    libssl-dev \
    libldap2-dev \
    && docker-php-ext-install intl

# Install extensions
RUN docker-php-ext-install pdo_mysql

# Install gd for maatwebsite/excel
RUN docker-php-ext-install gd

# Install zip
RUN docker-php-ext-install zip
RUN docker-php-ext-enable zip

# Install MongoDB PHP extensions
RUN pecl install mongodb-1.21.0
RUN docker-php-ext-enable mongodb

# Install Websocket extensions
RUN docker-php-ext-install pcntl
RUN docker-php-ext-configure pcntl --enable-pcntl
# RUN pecl install pcntl

# Install ldap
RUN docker-php-ext-install ldap

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install docker-compose
RUN curl -L "https://github.com/docker/compose/releases/download/{docker-compose_version}/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
RUN ln -s /usr/local/bin/docker-compose /usr/bin/docker-compose

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
# COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change detected dubious ownership
RUN git config --global --add safe.directory /var/www

# project composer require package install
# Install Composer and project dependencies.
RUN composer install

# Install Laravel Envoy(Auto Deploy Package - Blade)
RUN composer global require "laravel/envoy" --dev

# Install Node dependencies.
# - npm install (pass)

# Copy over testing configuration.
RUN cp .env.example .env

# Generate an application key. Re-cache.s
RUN php artisan key:generate
# RUN php artisan jwt:secret
# RUN php artisan cache:table
RUN php artisan config:cache
RUN php artisan storage:link

# Change current user to www
USER www

# Change storage verify
RUN chmod -R 755 storage
# RUN chown -R www-data:www-data storage

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]

