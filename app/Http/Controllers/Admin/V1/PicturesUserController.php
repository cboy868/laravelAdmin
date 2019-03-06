<?php

namespace App\Http\Controllers\Admin\V1;

use App\Common\ApiStatus;
use App\Entities\Pictures\Pictures;
use App\Entities\Pictures\Requests\StorePicturesRequest;
use App\Entities\Pictures\Requests\UpdatePicturesRequest;
use App\Entities\Pictures\User;
use Illuminate\Http\Request;
use App\Entities\Pictures\Repository\PicturesRepository;
use Cboy868\Repositories\Exceptions\RepositoryException;

/**
 * Class PicturesUserController
 * @package App\Http\Controllers\Admin\V1
 *
 * 关联模型处理  可参考文档：https://learnku.com/docs/laravel/5.5/eloquent-relationships/1333#the-save-method
 */
class PicturesUserController extends AdminController
{

    public $model;

    public function __construct(PicturesRepository $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::with('pictures')->find(1);
        $user->pictures()->detach(48);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::with('pictures')->find(1);
        $dtime = date('Y-m-d H:i:s');

        $user->pictures()->attach(48, ['created_at' => $dtime, 'updated_at'=>$dtime]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::with('pictures')->find(1);
        $user->pictures()->detach(48);
    }
}
