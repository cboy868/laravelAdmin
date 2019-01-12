<?php

namespace App\Http\Controllers\Api\Admin\V1;

use App\Common\ApiStatus;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends ApiController
{
    /**
     * Create a new AuthController instance.
     * 要求附带email和password（数据来源users表）
     * 
     * @return void
     */
    public function __construct()
    {
        // 这里额外注意了：官方文档样例中只除外了『login』
        // 这样的结果是，token 只能在有效期以内进行刷新，过期无法刷新
        // 如果把 refresh 也放进去，token 即使过期但仍在刷新期以内也可刷新
        // 不过刷新一次作废
        $this->middleware('auth:api', ['except' => ['login']]);
        // 另外关于上面的中间件，官方文档写的是『auth:api』
        // 但是我推荐用 『jwt.auth』，效果是一样的，但是有更加丰富的报错信息返回
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
            return $this->failed(ApiStatus::CODE_1001, session()->get(self::SESSION_ERR_KEY));
        }

        try {
            if (! $token = auth('api')->attempt($this->params)) {
                return $this->failed(ApiStatus::CODE_1051);
            }
            return $this->respondWithToken($token);
        } catch (JWTException $e) {
            Log::error(ApiStatus::CODE_1051 . __METHOD__ . __LINE__,[
                'code' => $e->getCode(),
                'msg' => $e->getMessage()
            ]);
            return $this->failed(ApiStatus::CODE_1051, "登录名或密码不匹配", 401);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->respond(['info'=>auth('api')->user()]);
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
            return $this->setCodeMsg("Successfully logged out")->respond();
        } catch (\Exception $e) {
            return $this->failed('Successfully logged out');
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
        return $this->respond([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
