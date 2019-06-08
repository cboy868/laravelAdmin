<?php

namespace App\Entities\Product\Events;

use App\Entities\Product\AttributeCategory;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class AttributeCategoryCreate
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $attributeCategory;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AttributeCategory $attributeCategory)
    {
        $this->attributeCategory = $attributeCategory;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
//        return new PrivateChannel('channel-name');
    }
}
