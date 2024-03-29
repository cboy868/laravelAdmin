<?php
namespace App\Http\Controllers\Admin\V1;


use App\Http\Controllers\ApiController;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Common\ApiStatus;


class UserController extends ApiController
{
	
	public function index(){

		$rules = [
			'name' => 'required',
			'password' => 'required'
		];

		$result = $this->_dealParams($rules);

		if (!$result) {
			return $this->failed(ApiStatus::CODE_1001, session()->get(self::SESSION_ERR_KEY));
		}

        try {
            if (! $token = JWTAuth::attempt($this->params)) {
                return $this->failed(ApiStatus::CODE_2001 );
            }

            return $this->respond(['token'=>$token]);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return $this->failed(ApiStatus::CODE_2001, $e->getMessage());
        }
	}
}