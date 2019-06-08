<?php

namespace App\Listeners;

use App\Entities\Product\Repository\AttributeCategoryRepository;
use App\Events\UserRegister;
use App\Entities\Product\Events\AttributeCategoryCreate as createEvent;

class AttributeCategoryCreate
{
    public $attributeCategoryRepository;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(AttributeCategoryRepository $attributeCategoryRepository)
    {
        $this->attributeCategoryRepository = $attributeCategoryRepository;
    }

    /**
     * Handle the event.
     *
     * @param  UserRegister  $event
     * @return void
     */
    public function handle(createEvent $event)
    {
        $this->attributeCategoryRepository->update();
    }
}
