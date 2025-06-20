    FROM php:8.3.8-fpm

    # Copy composer.lock dan composer.json ke /var/www
    COPY composer.lock composer.json /var/www/

    # Set sebagai working directory
    WORKDIR /var/www

    # Install dependencies yang diperlukan
    RUN apt-get update && apt-get install -y \
        build-essential \
        libpng-dev \
        libzip-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        locales \
        zip \
        jpegoptim optipng pngquant gifsicle \
        vim \
        unzip \
        git \
        curl 
    # Hapus cache
    RUN apt-get clean && rm -rf /var/lib/apt/lists/*

    # Jalankan mysqli
    RUN docker-php-ext-install mysqli pdo pdo_mysql zip exif pcntl

    # Install extensions
    # RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
    # RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
    # RUN docker-php-ext-install gd

    # Install composer
    RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

    # Add user for laravel application
    RUN groupadd -g 1000 www
    RUN useradd -u 1000 -ms /bin/bash -g www www

    # Copy existing application directory contents
    COPY . /var/www

    # Copy existing application directory permissions
    COPY --chown=www:www . /var/www

    # Change current user to www
    USER www

    # Expose port 9000 and start php-fpm server
    EXPOSE 9000
    CMD ["php-fpm"]