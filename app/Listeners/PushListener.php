<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Queue\InteractsWithQueue;
use JPush\Client;

class PushListener implements ShouldQueue
{
    protected $client;

    /**
     * Create the event listener.
     *
     * @param \JPush\Client $client
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Handle the event.
     *
     * @param \Illuminate\Notifications\DatabaseNotification $notification
     *
     * @return void
     */
    public function handle(DatabaseNotification $notification)
    {
        if (app()->environment('local')) {
            return;
        }

        $user = $notification->notifiable;

        if (! $user->registration_id) {
            return;
        }

        $this->client->push()
            ->setPlatform('all')
            ->addRegistrationId($user->registration_id)
            ->setNotificationAlert(strip_tags($notification->data['reply_count']))
            ->send();
    }
}
