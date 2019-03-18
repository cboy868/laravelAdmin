<?php

namespace App\Entities\Sms;

use App\Entities\Sms\Services\Dayu;
use App\Entities\Sms\Services\SmsInterface;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;

class DayuSmsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(SmsInterface::class, Dayu::class);
    }

    public function boot()
    {
        $this->app['validator']->extend('smscode', function($attribute, $value, $parameters)
        {
            //扩展 validator 可能有问题
//            return $value == Cache::get($parameters[0]);
        });
    }
}
