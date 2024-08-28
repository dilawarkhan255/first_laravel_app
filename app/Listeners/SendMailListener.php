<?php

namespace App\Listeners;

use App\Events\SendMailEvent;
use Illuminate\Support\Facades\Mail;

class SendMailListener
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\SendMailEvent  $event
     * @return void
     */
    public function handle(SendMailEvent $event)
    {
        Mail::raw($event->message, function($message) use ($event) {
            $message->to($event->user->email)
                    ->subject($event->subject);
        });
    }
}
