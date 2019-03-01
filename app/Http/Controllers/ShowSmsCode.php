<?php

namespace App\Http\Controllers;

use App\Repository\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Session\Store as Session;
use Illuminate\Hashing\BcryptHasher as Hasher;
use Illuminate\Support\Str;

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
    public function __invoke(Request $request)
    {
        $mobile = $request->input('mobile');

        return $this->respond([
            'code' => $this->generateCode($mobile)
        ]);
    }


    /**
     * 生成用户  并发送验证码
     * @param $mobile
     * @return bool|string
     */
    private function generateCode($mobile)
    {


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

        $hash = $this->hasher->make($code);
        $this->session->put('smscode', [
            'mobile' => $mobile,
            'code'       => $hash
        ]);

        return $this->session->get('smscode.mobile');

//            $this->session->remove('captcha');


        return $code;
    }
}
