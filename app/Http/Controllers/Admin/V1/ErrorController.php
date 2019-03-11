<?php

namespace App\Http\Controllers\Admin\V1;

use App\Common\ApiStatus;

class ErrorController extends AdminController
{
    public function noApi()
    {
        return $this->failed(ApiStatus::CODE_1002);
    }
}
