<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

//啟動格式php receive.php user
$user = strtolower($argv[1]);
$queueName = "queue_{$user}";

$connection = new AMQPStreamConnection('127.0.0.1', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare($queueName, false, true, false, false);

echo "正在接收留言：{$user}\n";

while (true) {
    $msg = $channel->basic_get($queueName);
    if ($msg) {
        $data = json_decode($msg->body, true);
        echo "收到：「{$data['message']}」\n";
        $channel->basic_ack($msg->delivery_info['delivery_tag']);
    }
    sleep(1);
}
?>
