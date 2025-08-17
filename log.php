<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('127.0.0.1', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->exchange_declare('events', 'topic', false, true, false);
$channel->queue_declare('log_queue', false, true, false, false);
$channel->queue_bind('log_queue', 'events', 'comments.#');

echo "留言紀錄服務啟動...\n";

while (true) {
    $msg = $channel->basic_get('log_queue');
    if ($msg) {
        $data = json_decode($msg->body, true);
        $logEntry = "[" . date('Y-m-d H:i:s') . "] {$data['name']}：{$data['message']}\n";
        file_put_contents("comments.log", $logEntry, FILE_APPEND);
        echo "已記錄留言：{$data['name']}\n";
        echo "留言內容：{$data['message']}\n";
        $channel->basic_ack($msg->delivery_info['delivery_tag']);
    }
    sleep(1);
}
?>
