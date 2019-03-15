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
use Illuminate\Support\Facades\Log;


class Dayu implements SmsInterface
{
    public function sendSms(String $mobile,array $params=[], String $template="SMS_158548272", String $sign_name="逗娱")
    {
        AlibabaCloud::accessKeyClient('LTAIBi7km0I0J3fy', 'uATqvG4LSsdfNclu8cD3gs0ZmAx7sW')
            ->regionId('cn-hangzhou') // replace regionId as you need
            ->asGlobalClient();

        try {
            $result = AlibabaCloud::rpcRequest()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->options([
                    'query' => [
                        'TemplateCode' => $template,
                        'PhoneNumbers' => $mobile,
                        'SignName' => $sign_name,
                        'TemplateParam' => json_encode($params),
//                        'SmsUpExtendCode' => 'sd',
                        'OutId' => 'd' . time(),
                    ],
                ])
                ->request();

            Log::error('SEND_SMS_' . __METHOD__, [
                'mobile' => $mobile,
                'params' => $params,
                'template' => $template,
                'sign_name' => $sign_name
            ]);

            return $result->toArray();
        } catch (ClientException $e) {

            echo $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        }

    }

}