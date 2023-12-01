<?php
namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;


class RabbitMQService
{
    private $connection;
    private $channel;
    private $queueName;

    public function __construct()
    {
//        $this->connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $this->connection = new AMQPStreamConnection(
            config('rabbitmq.host'),
            config('rabbitmq.port'),
            config('rabbitmq.user'),
            config('rabbitmq.pass'),
            config('rabbitmq.vhost'));
        $this->channel = $this->connection->channel();
        $this->queueName = 'authentication_events';
    }

    public function publish($message)
    {
        $this->channel->exchange_declare('test_exchange', 'direct', false, false, false);
        $this->channel->queue_declare($this->queueName, false, false, false, false);
        $this->channel->queue_bind($this->queueName, 'test_exchange', 'test_key');
        $msg = new AMQPMessage($message);
        $this->channel->basic_publish($msg, 'test_exchange', 'test_key');
//        echo " [x] Sent $message to test_exchange / test_queue.\n";
        $this->channel->close();
        $this->connection->close();
    }
    public function consume()
    {
        $callback = function ($msg)  {
            echo ' [x] Received ', $msg->body, "\n";
            return $msg->body;
        };
        $this->channel->queue_declare($this->queueName, false, false, false, false);
        $this->channel->basic_consume($this->queueName, '', false, true, false, false, $callback);
        echo 'Waiting for new message on test_queue', " \n";
        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
        $this->channel->close();
        $this->connection->close();
    }





}
