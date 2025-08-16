FROM php:8.2-cli

# 安裝 RabbitMQ 所需的系統套件與 PHP 擴充
RUN apt-get update && \
    apt-get install -y librabbitmq-dev libssl-dev unzip git curl && \
    pecl install amqp && \
    docker-php-ext-enable amqp

WORKDIR /app