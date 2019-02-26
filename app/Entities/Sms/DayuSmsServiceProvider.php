<?php

namespace App\Entities\Sms;

use App\Entities\Sms\Services\Dayu;
use App\Entities\Sms\Services\SmsInterface;
use Illuminate\Support\ServiceProvider;

class DayuSmsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(SmsInterface::class, Dayu::class);
    }
}
