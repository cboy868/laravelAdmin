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
            $token = $user->createToken('admin')->accessToken;
            return $this->respond(['token'=>$token]);
        } else {
            return $this->failed(ApiStatus::CODE_1501, '登录失败');
        }

    }


    /**
     * 退出登录
     */
    public function logout()
    {
        if (\Auth::guard('api')->check()) {
            \Auth::guard('api')->user()->token()->delete();
        }
        return response()->json(['message' => '登出成功', 'status_code' => 200, 'data' => null]);
    }



    /**
    * 请求时header 中带着 Authorization: 'Bearer '. $accessToken,
    */
    public function passport()
    {
        $userInfo = Auth::user();
        return $this->respond(['user_info'=>$userInfo]);
    }

}
