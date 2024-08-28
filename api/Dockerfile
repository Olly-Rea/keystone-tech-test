# Shared build between dev and prod
FROM php:8.3-fpm AS build

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

# Expose port 9000 for php-fpm server
EXPOSE 9000

# Run as development
FROM build AS development
# Install project dependencies
RUN composer install
# Run the key:generate artisan command
RUN php artisan key:generate
# Change current user to www
USER www
# Start the php-fpm server
CMD ["php-fpm"]

# Run as production
FROM build AS production
# Install project dependencies
RUN composer install --optimize-autoloader --no-dev
# Run the key:generate artisan command
RUN php artisan key:generate
# Optimise Laravel framework
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache
# Change current user to www
USER www
# Start the php-fpm server
CMD ["php-fpm"]
