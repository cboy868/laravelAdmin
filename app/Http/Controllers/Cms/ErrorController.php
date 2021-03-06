<?php

namespace App\Http\Controllers\Cms;

use App\Common\ApiStatus;
use App\Http\Controllers\ApiController;

class ErrorController extends ApiController
{
    public function noApi()
    {
        return $this->failed(ApiStatus::CODE_1002);
    }
}
