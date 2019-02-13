<?php

namespace App\Http\Controllers\Member\V1;

use App\Common\ApiStatus;

class ErrorController extends MemberController
{
    public function noApi()
    {
        return $this->failed(ApiStatus::CODE_1002);
    }
}
