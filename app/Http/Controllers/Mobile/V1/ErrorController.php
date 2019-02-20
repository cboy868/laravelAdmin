<?php

namespace App\Http\Controllers\Mobile\V1;

use App\Common\ApiStatus;

class ErrorController extends MobileController
{
    public function noApi()
    {
        return $this->failed(ApiStatus::CODE_1002);
    }
}
