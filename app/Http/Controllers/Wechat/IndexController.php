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

class IndexController extends Controller
{

    public function index()
    {
        return $this->wechat->server->serve();
    }
}

