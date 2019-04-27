<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/27
 * Time: 13:52
 */

namespace App\Entities\Wechat\Services;

use EasyWeChat\Factory;

class Wechat {

    public $wechat;

    public function __construct()
    {
        $config = [

            //测试账号
            'app_id' => 'wxae3fcf7fd4549053',
            'secret' => 'a5d948aa2caba62416f7654563c3e478',
            'token' => '7JPN8xArTFbvBgIjHXaDZdnwf3tQeY2c',
            'aes_key' => 'xiqs4UDgb6ZzXaK1pStdnIk7r0VmcfTH8wYNRu2yhJF',

            'response_type' => 'array',

            /**
             * 支付
             */
            'notify_url' => '',

            /**
             * 日志配置
             *
             * level: 日志级别, 可选为：
             *         debug/info/notice/warning/error/critical/alert/emergency
             * path：日志文件位置(绝对路径!!!)，要求可写权限
             */
            'log' => [
                'default' => 'dev', // 默认使用的 channel，生产环境可以改为下面的 prod
                'channels' => [
                    // 测试环境
                    'dev' => [
                        'driver' => 'single',
                        'path' => '/tmp/easywechat.log',
                        'level' => 'debug',
                    ],
                    // 生产环境
                    'prod' => [
                        'driver' => 'daily',
                        'path' => '/tmp/easywechat.log',
                        'level' => 'info',
                    ],
                ],
            ],

            /**
             * 接口请求相关配置，超时时间等，具体可用参数请参考：
             * http://docs.guzzlephp.org/en/stable/request-config.html
             *
             * - retries: 重试次数，默认 1，指定当 http 请求失败时重试的次数。
             * - retry_delay: 重试延迟间隔（单位：ms），默认 500
             * - log_template: 指定 HTTP 日志模板，请参考：https://github.com/guzzle/guzzle/blob/master/src/MessageFormatter.php
             */
            'http' => [
                'max_retries' => 1,
                'retry_delay' => 500,
                'timeout' => 5.0,
                // 'base_uri' => 'https://api.weixin.qq.com/', // 如果你在国外想要覆盖默认的 url 的时候才使用，根据不同的模块配置不同的 uri
            ],

            /**
             * OAuth 配置
             *
             * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
             * callback：OAuth授权完成后的回调页地址
             */
            'oauth' => [
                'scopes' => ['snsapi_userinfo'],
                'callback' => '/api/client/wechat-auth-callback',
            ],
        ];

        $this->wechat = Factory::officialAccount($config);
    }


    public function getPayInstance()
    {

        $config = [
            // 必要配置
            'app_id' => 'wxae3fcf7fd4549053',
            'mch_id' => '1518168341',
            'key' => 'Zhaoyekun23168814722222222222222',   // API 密钥

            // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
//            'cert_path' => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
//            'key_path' => 'path/to/your/key',      // XXX: 绝对路径！！！！

            'notify_url' => '/api/client/notify',     // 你也可以在下单时单独设置来想覆盖它
        ];


        return Factory::payment($config);
    }

}