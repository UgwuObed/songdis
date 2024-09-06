<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Illuminate\Foundation\Bus\Dispatchable;

class SendContactsToQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $contacts;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($contacts)
    {
        $this->contacts = $contacts;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_USER'),
            env('RABBITMQ_PASSWORD')
        );

        $channel = $connection->channel();
        $channel->queue_declare('contact_queue', false, true, false, false);

        foreach ($this->contacts as $contact) {
            $message = new AMQPMessage(json_encode($contact));
            $channel->basic_publish($message, '', 'contact_queue');
        }

        $channel->close();
        $connection->close();
    }
}
