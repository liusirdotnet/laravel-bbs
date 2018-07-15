<?php

namespace App\Events\Admin;

use Illuminate\Queue\SerializesModels;

class AlertEvent
{
    use SerializesModels;

    protected $collection;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $collection)
    {
        $this->collection = $collection;

        event('admin.alert.collection', $collection);
    }
}
