FROM php:8.3-cli

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    libicu-dev \
    zip \
    unzip \
    gnupg \
    procps \
    build-essential

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql zip exif pcntl bcmath gd intl

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install NodeJS (v20 LTS)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Expose ports for artisan serve and vite dev server
EXPOSE 8000
EXPOSE 5173

# Entrypoint default
CMD ["npm", "run", "dev"]
