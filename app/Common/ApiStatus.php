<?php

namespace App\Common;

/**
 * api 状态码
 * Class ApiStatus
 */
class ApiStatus {

	/**
	 * @var string 请求成功
	 */
    const CODE_0 = '0';         // 成功
    //-+----------------------------------------------------------------------
    // | 接口协议级别 请求 错误
    //-+----------------------------------------------------------------------
    const CODE_1001 = '1001';//缺少参数


    //授权部分
    const CODE_1051 = '1051';//授权失败

    //用户部分
    const CODE_2001 = '2001';//登录失败

	/**
	 * @var string 接口请求出错：空请求
	 */
    const CODE_10100 = '10100';//空请求
	/**
	 * @var string 接口请求出错：格式错误
	 */
    const CODE_10101 = '10101';//格式错误
	/**
	 * @var string 接口请求出错：appid 错误
	 */
    const CODE_10102 = '10102';//appid 错误
	/**
	 * @var string 接口请求出错：method 错误
	 */
    const CODE_10103 = '10103';//method 错误
	/**
	 * @var string 接口请求出错：params	 错误
	 */
    const CODE_10104 = '10104';//params	 错误
	/**
	 * @var string 接口请求出错：version 错误
	 */
    const CODE_10105 = '10105';//version 错误

    //-+----------------------------------------------------------------------
    // | 业务参数错误
    //-+----------------------------------------------------------------------
	/**
	 * @var string 业务参数错误：参数必须，或参数值错误
	 */
    const CODE_20001 = '20001';// 参数必须，或参数值错误
    const CODE_20002 = '20002';//数据存储失败
    const CODE_20003 = '20003';//app版本不存在
    const CODE_20004 = '20004';//获取用户信息失败
    const CODE_20005 = '20005';//版本不存在

    public static $errCodes = [
        self::CODE_0     => 'success',

        self::CODE_1051 => 'Unauthorized',

        self::CODE_2001 => 'logout failed',


        self::CODE_10100 => '空请求',
        self::CODE_10101 => '请求格式错误',
        self::CODE_10102 => '渠道错误',//[appid]
        self::CODE_10103 => '方法错误',//[method]
        self::CODE_10104 => '参数错误',//[params]
        self::CODE_10105 => '版本错误',//[version]

        self::CODE_20001 => '参数必须',
        self::CODE_20002 => '数据存储失败',
        self::CODE_20003 => 'app版本不存在',
        self::CODE_20006 => '认证失败',

    ];
}
