<?php

namespace App\Http\Controllers;

use App\Entities\Sms\Services\SmsInterface;
use App\Http\Requests\SmsCodeRequest;
use App\Repository\UserRepository;
use Illuminate\Session\Store as Session;
use Illuminate\Hashing\BcryptHasher as Hasher;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class ShowSmsCode extends ApiController
{
    public $model;
    public $session;
    public $hasher;
    public $str;

    public function __construct(UserRepository $user, Session $session, Hasher $hasher, Str $str)
    {
        $this->model = $user;
        $this->session = $session;
        $this->hasher = $hasher;
        $this->str = $str;
    }

    /**
     * //生成短信验证码
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke(SmsCodeRequest $request, SmsInterface $sms)
    {
        $mobile = $request->input('mobile');

        if ($code = $this->generateCode($mobile)) {
            $sms->sendSms($mobile, ['code'=>$code]);
            return $this->respond();
        }

        return $this->failed();
    }


    /**
     * 生成用户  并发送验证码
     * @param $mobile
     * @return bool|string
     */
    private function generateCode($mobile)
    {
        try {
            //根据验证码查找用户,如果没有则新生成
            $model = $this->model->findBy('mobile', $mobile);
            if (!$model) {
                $this->model->create([
                    'name' => $mobile,
                    'mobile' => $mobile
                ]);
            }

            //生成验证码并存入session
            $code = random_str(6);

            //10分钟内有效
            Cache::put($mobile, $code, 10);
        } catch (\Exception $e) {
            Log::error(__METHOD__ . __LINE__, [
                'msg' => $e->getMessage()
            ]);

            return false;
        }

        return $code;
    }
}
