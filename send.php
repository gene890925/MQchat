<?php
// 隱藏所有 deprecation 警告
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', 1);

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('hello_msg', false, true, false, false);

$msg = new AMQPMessage('Hello world');

$channel->basic_publish($msg, '', 'hello_msg');

echo "hello訊息已送出";

$channel->close();
$connection->close();
