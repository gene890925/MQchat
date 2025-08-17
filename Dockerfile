FROM php:8.2-apache

# 安裝 RabbitMQ 所需套件與 PHP 擴充
RUN apt-get update && \
    apt-get install -y librabbitmq-dev libssl-dev unzip git curl && \
    pecl install amqp && \
    docker-php-ext-enable amqp && \
    docker-php-ext-install bcmath

# 安裝 Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

COPY . /var/www/html/

