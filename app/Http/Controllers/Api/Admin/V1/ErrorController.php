<?php

namespace App\Http\Controllers\Api\Admin\V1;

use App\Common\ApiStatus;
use App\Http\Controllers\ApiController;

class ErrorController extends ApiController
{
    public function noApi()
    {
        return $this->failed(ApiStatus::CODE_1002);
    }
}
