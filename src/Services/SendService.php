<?php

namespace Usoft\RabbitMq\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class SendService
{
    protected $channel;
    protected $connection;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(env('RABBITMQ_HOST'), env('RABBITMQ_PORT'), env('RABBITMQ_USER'), env('RABBITMQ_PASSWORD'));
        $this->channel = $this->connection->channel();
    }

    public function queue_declare($queue, $data)
    {
        $this->channel->queue_declare($queue, false, false, false, false);

        $msg = new AMQPMessage(json_encode($data));
        $this->channel->basic_publish($msg, '', $queue);

        $this->channel->close();
        $this->connection->close();
    }
}
