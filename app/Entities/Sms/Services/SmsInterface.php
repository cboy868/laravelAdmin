<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/2/26
 * Time: 10:37
 */

namespace App\Entities\Sms\Services;


interface SmsInterface
{
    public function sendSms(String $mobile,array $params=[], String $template, String $sign_name);
}