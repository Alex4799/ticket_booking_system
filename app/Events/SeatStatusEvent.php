<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SeatStatusEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $movie_plan_id;
    public $seat_id;
    public $status;
    /**
     * Create a new event instance.
     */
    public function __construct($movie_plan_id,$seat_id,$status)
    {
        $this->movie_plan_id=$movie_plan_id;
        $this->seat_id=$seat_id;
        $this->status=$status;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return ['seat-status-channel'];
    }

    public function broadcastAs(){
        return 'seat-status-event';
    }
}
