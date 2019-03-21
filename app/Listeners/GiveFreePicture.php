<?php

namespace App\Listeners;

use App\Entities\Pictures\Repository\UserRepository;
use App\Events\FirstLogin;

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
     * @param  FirstLogin  $event
     * @return void
     */
    public function handle(FirstLogin $event)
    {
        $user = $this->userRepository->with('pictures')
            ->find($event->user->id);

        $dtime = date('Y-m-d H:i:s');

        $pictures_id = config('blog.freePicturesId');

        $user->pictures()->attach($pictures_id, ['created_at' => $dtime, 'updated_at'=>$dtime]);
    }
}
