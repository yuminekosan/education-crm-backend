FROM php:7.4-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

RUN apt-get update \
    && docker-php-ext-install pdo pdo_mysql


# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www/diplom

COPY --chown=$user:$user . /var/www/diplom
RUN chmod -R 775 /var/www/diplom/storage
USER $user
