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


    const CODE_1051 = '1051';//授权失败

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


    const CODE_20006 = '20006';//第三方认证登录失败


    const CODE_30000 = '30000';//未知错误
    const CODE_30001 = '30001';//风控未认证
    const CODE_30002 = '30002';//未进行实名认证

    const CODE_40001 = '40001';//权限问题，提示没有登录吧

    //-+----------------------------------------------------------------------
    // | 内部服务异常错误
    //-+----------------------------------------------------------------------
    /**
     * @var string 状态码：程序异常（程序未捕获的异常：程序发生致命错误）
     */
    const CODE_50000 = '50000'; //

    //-+----------------------------------------------------------------------
    // | 依赖接口错误
    //-+----------------------------------------------------------------------
    /**
     * @var string 状态码：依赖接口错误（调用第三方接口时失败）
     */
    const CODE_60000 = '60000'; //
    /**
     * @var string 状态码：数据未获取成功（调用第三方接口时失败）
     */
    const CODE_60001 = '60001'; //数据未获取成功
    /**
     * @var string 状态码：第三方报错（调用第三方接口时失败）
     */
    const CODE_60002 = '60002'; //第三方报错
    const CODE_60003 = '60003'; //请输入短信验证码
    const CODE_60004 = '60004'; //请输入二次鉴权短信验证码
    const CODE_60005 = '60005'; //本地区不支持些操作
    const CODE_60006 = '60006'; //请求成功，需传入额外参数
    const CODE_60007 = '60007'; //重置密码提示




    //-------------- face++错误 ------------------------
    //身份证上传
    const CODE_60100= '60100'; //身份证照片未上传,
    const CODE_60101= '60101'; //身份证国徽面不清晰,
    const CODE_60102= '60102'; //身份证人脸面不清晰,
    const CODE_60103= '60103'; //身份证过期,
    const CODE_60104= '60104'; //身份信息不匹配,
    //人脸扫描


    //-------------- 认证错误 ------------------------
    const CODE_60300= '60300'; //芝麻未认证,
    const CODE_60401= '60401'; //订单完结押金返还失败
    const CODE_60402= '60402'; //订单取消押金返还失败
    const CODE_60403= '60403'; //下单使用押金失败







    public static $errCodes = [
        self::CODE_0     => '成功',
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


        self::CODE_30000 => '未知错误',
        self::CODE_30001 => '风控未认证',
        self::CODE_30002 => '未认证',

        self::CODE_50000 => '程序异常',
        self::CODE_60000 => '接口调用出错',
        self::CODE_60001 => '数据未获取成功',
        self::CODE_60002 => '接口调用报错',
        self::CODE_60003 => '请输入短信验证码',
        self::CODE_60004 => '请输入二次鉴权短信验证码',
        self::CODE_60005 => '本地区不支持些操作',
        self::CODE_60006 => '请求成功，需传入额外参数',
        self::CODE_60007 => '重置密码提示',

        self::CODE_60100 => '请上传身份证照片', //身份证照片未上传,
        self::CODE_60101 => '您好，请上传清晰国徽面身份证照片', //身份证国徽面不清晰,
        self::CODE_60102 => '您好，请上传清晰人脸面身份证照片', //身份证人脸面不清晰,
        self::CODE_60103 => '您的身份证已过期,请上传最新二代身份证', //身份证过期,
        self::CODE_60104 => '您好，您上传的身份证信息与认证信息不匹配',//信息不匹配

        self::CODE_60300 => '您好，请先进行芝麻认证', //芝麻未认证,
        self::CODE_60401 => '订单完结押金返还失败',
        self::CODE_60402 => '订单取消押金返还失败',

    ];
}
