<?php

namespace App\Entities\Sms\Services;


use Illuminate\Support\Facades\Cache;

/**
* 验证码类
*/
class Code
{
	
	public function generateCode($mobile, $length=6)
	{
        try {
        	$code = random_str($length);
        	//10分钟内有效
        	Cache::put($mobile, $code, 10);

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