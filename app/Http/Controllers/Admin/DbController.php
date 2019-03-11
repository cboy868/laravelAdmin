<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;

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