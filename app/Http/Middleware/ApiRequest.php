<?php

namespace App\Http\Middleware;

use App\Common\ApiStatus;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Closure;
use Illuminate\Support\Facades\Log;

class ApiRequest
{
    use DispatchesJobs;
    use ValidatesRequests;
    use ApiResponse;

    public function handle($request, Closure $next)
    {
//        $params = $request->input();
//        $rules = [
//            'appid' => 'required',
//            'method'=> 'required',
//            'version' => 'required'
//        ];
//
//        $validator = app('validator')->make($params, $rules);
//        if ($validator->fails()) {
//            Log::error(__METHOD__ . __LINE__, [
//                'errors' => $validator->errors()
//            ]);
//            return $this->setCodeMsg($validator->errors()->first())->failed(ApiStatus::CODE_1001);
//        }

        return $next($request);
    }
}
