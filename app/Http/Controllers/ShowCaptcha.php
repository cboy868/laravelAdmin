<?php

namespace App\Http\Controllers;

class ShowCaptcha extends ApiController
{
    /**
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke()
    {
        return $this->respond([
            'captcha' => captcha_src()
        ]);
    }
}
