<?php

namespace App\Listeners;

use App\Entities\Pictures\Repository\UserRepository;
use App\Events\UserLogin;

class LoginTimes
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
     * @param  UserLogin  $event
     * @return void
     */
    public function handle(UserLogin $event)
    {
        //登录次数增加
        $event->user->increment('login_times');
    }
}
