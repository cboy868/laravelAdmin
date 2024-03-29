<?php

namespace App\Listeners;

use App\Entities\Pictures\Repository\UserRepository;
use App\Events\UserRegister;

class GiveFreePicture
{
    public $userRepository;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Handle the event.
     *
     * @param  UserRegister  $event
     * @return void
     */
    public function handle(UserRegister $event)
    {
        //赠送免费章节
        $pictures_id = config('blog.freePicturesId');
        $this->userRepository->givePicture($event->user->id, $pictures_id);
    }
}
