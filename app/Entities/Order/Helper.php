<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/25
 * Time: 22:19
 */
namespace App\Entities\Order;

class Helper
{
    /**
     * 生成各种单号
     * @param int $orderType
     * @return string
     */
    public static function createOrderNo($orderType = 1)
    {
        $year = [];

        for ($i = 65; $i < 91; $i++) {
            $year[] = strtoupper(chr($i));
        }
        $orderSn = $year[(intval(date('Y'))) - 2018] .
            strtoupper(dechex(date('m'))) .
            date('d') .
            $orderType . substr(time(), -5) .
            substr(microtime(), 2, 5) .
            rand(0, 9);

        return $orderSn;
    }

    /**
     * 生成本地支付单号
     * @param int $orderType
     * @return string
     */
    public static function createPayNo($orderType = 1)
    {
        $year = [];

        for ($i = 65; $i < 91; $i++) {
            $year[] = strtoupper(chr($i));
        }
        $orderSn = 'PAY' . $year[(intval(date('Y'))) - 2018] .
            strtoupper(dechex(date('m'))) .
            date('d') .
            $orderType . substr(time(), -5) .
            substr(microtime(), 2, 5) .
            rand(0, 9);

        return $orderSn;
    }
}