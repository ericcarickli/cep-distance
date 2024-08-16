# Dockerfile
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev git unzip \
    libssl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest

# Install MongoDB PHP extension
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Install Redis PHP extension
RUN pecl install redis \
    && docker-php-ext-enable redis

# Set working directory
WORKDIR /var/www

# Copy application code
COPY . /var/www

RUN cp .env.example .env

# Install PHP and Node.js dependencies
RUN composer install
RUN npm install

# Expose ports
EXPOSE 8000 5173

# Start the application
CMD ["sh", "-c", "npm run dev & php artisan serve --host=0.0.0.0 --port=8000"]
