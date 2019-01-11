<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Auth;
use App\Common\ApiStatus;


class UserController extends ApiController
{
    public function login()
    {

        $rules = [
            'name' => 'required',
            'password' => 'required',
        ];

        if(!$this->_dealParams($rules)){
            return $this->failed(ApiStatus::CODE_1001,session()->get(self::SESSION_ERR_KEY));
        }


        if(Auth::attempt(['name' => $this->params['name'], 'password' => $this->params['password']]))
        {
            $user = Auth::user();
            $this->content['token'] =  $user->createToken('Pi App')->accessToken;
            $status = 200;
        } else {

            $this->content['error'] = "未授权";
             $status = 401;
        }
         return response()->json($this->content, $status);
    }

    public function passport()
    {
        return response()->json(['user' => Auth::user()]);
    }

}
