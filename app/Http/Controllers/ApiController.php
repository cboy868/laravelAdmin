<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use App\Common\ApiStatus;


class ApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    use \App\Traits\ApiResponse;

    const SESSION_ERR_KEY = 'fk.error';

    const DEFAULT_PAGE_SIZE = 25;

    const PAGE_SIZE_ONE = 'page_size_one';
    const PAGE_SIZE_TWO = 'page_size_two';
    const PAGE_SIZE_FIVE= 'page_size_five';

    protected $appid;

    protected $authToken;

    protected $version;

    protected $method;

    protected $params;

    protected $user;

    static $pageSize = [
        self::PAGE_SIZE_ONE => 10,
        self::PAGE_SIZE_TWO => 20,
        self::PAGE_SIZE_FIVE => 50
    ];

    public function __construct()
    {

        //临时
//        $this->middleware('auth:member')->except(['index', 'show']);

        //临时注释
       // $prefix = request()->route()->getPrefix();

       // if ($prefix == "api/client") {
       //     //除了 index show 其它方法都需要登录
       //     $this->middleware('auth:member')->except(['index', 'show']);
       // } else if ($prefix == 'api/admin') {
       //     $this->middleware('auth:admin')->except(['login']);
       // }

    }

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
                Log::error('api-params-deal-error',[
                    'param'=>$param,
                    'rules'=>$rules,
                    'errors' => $validator->errors()
                ]);
                session()->flash(self::SESSION_ERR_KEY, $validator->errors()->first());

                return false;
                // return [];
                // return $this->failed(ApiStatus::CODE_1001,session()->get(self::SESSION_ERR_KEY));
            }
        } else {
            $param = [];
        }

        $this->appid = $params['appid'];
        $this->authToken = isset($params['auth_token']) ?? $params['auth_token'];
        $this->version = isset($params['version']) ? $params['version'] : '1.0';
        $this->method = $params['method'];

        $this->params = $param;

        $this->user = auth('api')->user();

        return true;
    }





}
