<?php
// 隱藏所有 deprecation 警告
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', 1);

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$name = $_POST['name'];
$message = $_POST['message'] ;

if (empty($message)) {
    http_response_code(400);
    echo json_encode(['error' => '留言內容不能為空']);
    exit;
}

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->exchange_declare('events', 'topic', false, true, false);

$queueName = "queue_" . strtolower($name);
$channel->queue_declare($queueName, false, true, false, false);

$data = json_encode(['name' => $name, 'message' => $message]);
$msg = new AMQPMessage($data);
$channel->basic_publish($msg, '', $queueName);

$logMsg = new AMQPMessage($data);
$channel->basic_publish($logMsg, 'events', 'comments.' . strtolower($name));

// 回傳 JSON 回應
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'message' => "發送留言成功",
    'data' => [
        'name' => $name,
        'message' => $message,
        'queue' => $queueName
    ]
]);

$channel->close();
$connection->close();
?>
