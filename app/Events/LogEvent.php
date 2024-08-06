<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LogEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user, $request, $message, $class, $function, $new, $old;

    public function __construct($user, $request, $message, $class, $function, $new = null, $old = null)
    {
        $this->user = $user;
        $this->request = $request;
        $this->message = $message;
        $this->class = $class;
        $this->function = $function;
        $this->new = $new;
        $this->old = $old;
    }

}
