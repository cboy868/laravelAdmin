<?php

namespace App\Entities\Sms\Services;


use Illuminate\Support\Facades\Cache;

/**
* 验证码类
*/
class Code
{
	
	public function generateCode($mobile, $length=4)
	{
        try {
        	$code = random_str($length, true);
        	//10分钟内有效
        	Cache::put($mobile, $code, 10);

        	//限制一天内10次验证码
            $key = $mobile . '_times';

            if (Cache::get($key) > 10) {
                throw new \Exception("获取验证码次数过多");
            }

            if (Cache::get($key)) {
                Cache::put($key, Cache::get($key) +1);
            } else {
                Cache::put($key, 1);
            }

        	return $code;
        } catch (\Exception $e) {
        	return false;
        }
	}

	public function check($mobile, $code)
	{
		//验证smscode
        $catchCode = Cache::get($mobile);

        return strtolower($catchCode) == strtolower($code);
	}

}