# Set the PHP version to use
ARG PHP_VERSION=8.3.10

# Shared build between dev and prod
FROM php:${PHP_VERSION} AS build
# Set working directory
WORKDIR /srv
# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    libonig-dev \
    libtidy-dev
# Clear apt cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl tidy
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www
# Copy the application code (setting www:www as user:group)
COPY --chown=www:www . .
# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Install project dependencies
RUN composer install --optimize-autoloader --no-dev
# Run the key:generate artisan command
RUN php artisan key:generate
# Optimise Laravel framework
RUN php artisan config:cache
RUN php artisan route:cache


FROM php:${PHP_VERSION}-fpm-alpine3.19
# Install dependencies
RUN apk update && apk add \
    libzip-dev \
    oniguruma-dev \
    tidyhtml-dev
# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl tidy
# Change current user to www
RUN addgroup -S www && adduser -S www -G www
USER www
# Copy files from build
COPY --chown=www:www --from=build /srv /srv/
# Expose port 9000 for php-fpm server
EXPOSE 9000
# Start the php-fpm server
CMD ["php-fpm"]
