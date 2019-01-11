<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use App\Common\ApiStatus;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    use \App\Traits\ApiResponse;

    const SESSION_ERR_KEY = 'fk.error';

    protected $appid;

    protected $authToken;

    protected $version;

    protected $method;

    protected $params;


    protected function _dealParams($rules=[])
    {
        $params = request()->input();

        if (isset($params['params'])) {
            if (is_string($params['params'])) {
                $param = json_decode($params['params'], true);
            } else if (is_array($params['params'])) {
                $param = $params['params'];
            }

            $validator = app('validator')->make($param, $rules);


            if ($validator->fails()) {
                Log::error('api-params-deal-error',['param'=>$param,'rules'=>$rules]);
                session()->flash(self::SESSION_ERR_KEY, $validator->errors()->first());

                return false;
                // return [];
                // return $this->failed(ApiStatus::CODE_1001,session()->get(self::SESSION_ERR_KEY));
            }
        }

        $this->appid = $params['appid'];
        $this->authToken = isset($params['auth_token']) ?? $params['auth_token'];
        $this->version = isset($params['version']) ? $params['version'] : '1.0';
        $this->method = $params['method'];

        $this->params = $param;

        return true;
    }


    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('name', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return response()->json(compact('token'));
    }
}
