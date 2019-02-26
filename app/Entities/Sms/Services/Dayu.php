<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/2/26
 * Time: 10:43
 */
namespace App\Entities\Sms\Services;


use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;


class Dayu implements SmsInterface
{
    public function sendSms()
    {
        AlibabaCloud::accessKeyClient('<accessKeyId>', '<accessSecret>')
            ->regionId('cn-hangzhou') // replace regionId as you need
            ->asGlobalClient();

        try {
            $result = AlibabaCloud::rpcRequest()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('QuerySendDetails')
                ->method('POST')
                ->options([
                    'query' => [
                        'PhoneNumber' => '15910470214',
                        'SendDate' => '2019-01-12 12:12:21',
                        'PageSize' => '2',
                        'CurrentPage' => '1',
                        'BizId' => '123123213',
                    ],
                ])
                ->request();
            print_r($result->toArray());
        } catch (ClientException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        }
    }
}