<?php

namespace App\Listeners;

use App\Events\FirstLogin;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GiveFreePicture
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  FirstLogin  $event
     * @return void
     */
    public function handle(FirstLogin $event)
    {
        info("do something what to do",[
            'a' => $event
        ]);
    }
}
