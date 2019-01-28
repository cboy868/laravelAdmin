<?php

namespace App\Http\Controllers\Admin\V1;

use App\Common\ApiStatus;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends AdminController
{
    /**
     * Create a new AuthController instance.
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $rules = [
            'name' => 'required',
            'password' => 'required'
        ];
        if (!$this->_dealParams($rules)) {
            return $this->setCodeMsg(session()->get(self::SESSION_ERR_KEY))->failed(ApiStatus::CODE_2002);
        }

        try {
            if (! $token = auth('api')->attempt($this->params)) {
                return $this->failed(ApiStatus::CODE_2001);
            }
            return $this->respondWithToken($token);
        } catch (JWTException $e) {
            Log::error(ApiStatus::CODE_2001 . __METHOD__ . __LINE__,[
                'code' => $e->getCode(),
                'msg' => $e->getMessage()
            ]);
            return $this->failed(ApiStatus::CODE_2001, "登录名或密码不匹配", 401);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->success(['info'=>auth('api')->user()]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try{
            auth('api')->logout();
            return $this->setCodeMsg("Successfully logged out")->success();
        } catch (\Exception $e) {
            return $this->failed(ApiStatus::CODE_1001);
        }
    }

    /**
     * Refresh a token.
     * 刷新token，如果开启黑名单，以前的token便会失效。
     * 值得注意的是用上面的getToken再获取一次Token并不算做刷新，两次获得的Token是并行的，即两个都可用。
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return $this->success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
