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
            'app_id' => 'wxa49d94dde698d291',
            'secret' => 'db9f2d31ee80a622568d7f6eab3649c8',
            'token' => '7JPN8xArTFbvBgIjHXaDZdnwf3tQeY2c',
            'response_type' => 'array',
        ];

        $this->wechat = Factory::officialAccount($config);
    }


    public function index()
    {
        return $this->wechat->server->serve();
    }
}

