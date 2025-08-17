<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('127.0.0.1', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('hello_msg', false, true, false, false);

echo "等待訊息...\n";

while (true) {
    $msg = $channel->basic_get('hello_msg');
    if ($msg) {
        echo "收到: " . $msg->body . "\n";
        $channel->basic_ack($msg->delivery_info['delivery_tag']);
    }
    sleep(1);
}
?>
