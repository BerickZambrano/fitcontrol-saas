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
    supervisor \
    build-essential

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql zip exif pcntl bcmath gd intl

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Increase upload limits
RUN echo "upload_max_filesize = 50M" >> /usr/local/etc/php/conf.d/docker-php-ext-upload.ini \
    && echo "post_max_size = 50M" >> /usr/local/etc/php/conf.d/docker-php-ext-upload.ini


# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install NodeJS (v20 LTS)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Copy Supervisor configuration
COPY docker/supervisor.conf /etc/supervisor/conf.d/fitcontrol.conf

# Expose ports for artisan serve and vite dev server
EXPOSE 8000
EXPOSE 5173

# In production: start supervisord (manages queue workers + scheduler)
# In development: docker-compose overrides this with 'tail -f /dev/null'
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
