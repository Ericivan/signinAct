<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DatabaseEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;

    public $syncDb;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data,$syncDb=['dev'])
    {
        $this->data = $data;

        $this->syncDb = $syncDb;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
