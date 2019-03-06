<?php
namespace App\Http\Controllers\Admin\V1;


use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Common\ApiStatus;


class DbController extends AdminController
{
	
	public function index(){

        $list = DB::select('show tables');
        $table = [];
        foreach ($list as $ta){

            $info = DB::select("describe " . $ta->Tables_in_zuji_fengkong);

            $table[$ta->Tables_in_zuji_fengkong] = $info;
        }

        return $this->respond($table);
    }
}