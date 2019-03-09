<?php

namespace App\Http\Controllers\Client;

use App\Entities\Pictures\Repository\PicturesRepository;

class PicturesController extends \App\Http\Controllers\Cms\PicturesController
{
    public function __construct(PicturesRepository $model)
    {
        //加权限
        $this->middleware('auth:member')->except(['index', 'show']);

        parent::__construct($model);
    }
}
