1. 啟動php 與 RabbitMQ：
   ```bash
   docker-compose up --build -d
   ```

1. 建立完容器後，進入 PHP 容器：
   ```bash
   docker-compose exec php bash
   ```

1. 在容器內使用 Composer 安裝 RabbitMQ 客戶端：
   ```bash
   composer require php-amqplib/php-amqplib
   ```