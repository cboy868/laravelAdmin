<?php
namespace App\Http\Controllers\Wechat;
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/3/5
 * Time: 17:27
 */


use EasyWeChat\Factory;
use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    public $wechat;

    public function __construct()
    {
        $config = [
            'app_id' => 'wxf2831524143015af',
            'secret' => 'fa7aec57b599c0e1155b4ba19857a8f5',
            'token' => '7JPN8xArTFbvBgIjHXaDZdnwf3tQeY2c',
            'response_type' => 'array',
        ];

        $this->wechat = Factory::officialAccount($config);
    }


    public function index()
    {
//        return $this->wechat->server->serve();
    }
}

