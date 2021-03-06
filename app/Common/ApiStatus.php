<?php

namespace App\Common;

/**
 * api 状态码
 * Class ApiStatus
 */
class ApiStatus {

    const CODE_0 = '0';         // 成功
    //-+----------------------------------------------------------------------
    // | 接口协议级别 请求 错误
    //-+----------------------------------------------------------------------
    const CODE_1001 = '1001';//缺少参数或错误

    const CODE_1002 = '1002';//方法错误

    const CODE_1011 = '1011';//操作失败

    const CODE_1021 = '1021';//资源不存在

    const CODE_1022 = '1022';//资源类型错误

    const CODE_1099 = '1099';//未知错误类型，获取不到正常错误码时返回
    //-+----------------------------------------------------------------------
    // | 业务参数错误
    // | 用户部分
    //-+----------------------------------------------------------------------
    //用户部分
    const CODE_2001 = '2001';//登录失败
    //用户授权部分
    const CODE_2002 = '2002';//授权失败
    //授权过期
    const CODE_2003 = '2003';//授权过期
    //授权过期
    const CODE_2004 = '2004';//缺少token

    //-+----------------------------------------------------------------------
    // | 业务参数错误
    // | 短信
    //-+----------------------------------------------------------------------
    const CODE_2051 = '2051';//短信验证码发送错误，请重试

    //-+----------------------------------------------------------------------
    // | 业务参数错误
    // | 文章部分
    //-+----------------------------------------------------------------------
    const CODE_3001 = '3001';//添加失败

    //-+----------------------------------------------------------------------
    // | 业务参数错误
    // | 文件及目录
    //-+----------------------------------------------------------------------

    const CODE_3051 = '3051';//创建目录失败
    const CODE_3052 = '3052';//目录删除失败
    const CODE_3053 = '3053';//文件上传失败
    const CODE_3054 = '3054';//文件删除失败
    const CODE_3055 = '3055';//目录已存在
    const CODE_3056 = '3056';//目录非空
    const CODE_3057 = '3057';//文件未找到
    const CODE_3058 = '3058';//文件已存在

    //-+----------------------------------------------------------------------
    // | 业务参数错误
    // | 商品
    //-+----------------------------------------------------------------------
    const CODE_4001 = '4001';//商品不存在

    const CODE_4002 = '4002';//订单商品添加失败

    const CODE_4003 = '4003';//订单下单失败

    const CODE_4004 = '4004';//付费内容

    const CODE_4005 = '4005';//支付失败



    public static $errCodes = [
        self::CODE_0    => 'success',
        self::CODE_1001 => 'params required',
        self::CODE_1002 => 'method wrong',
        self::CODE_1011 => 'operation failed',
        self::CODE_1021 => 'resource not existed',
        self::CODE_1022 => 'data type error',
        self::CODE_1099 => 'unknown error',

        self::CODE_2001 => 'login failed',
        self::CODE_2002 => 'Unauthorized',
        self::CODE_2003 => 'Token Expired',
        self::CODE_2004 => 'Token Required',
        self::CODE_2051 => 'sms code error',

        self::CODE_3001 => 'Add failure',
        self::CODE_3051 => 'Create folder error',
        self::CODE_3052 => 'Delete folder error',
        self::CODE_3053 => 'upload file error',
        self::CODE_3055 => 'folder already exists',
        self::CODE_3056 => 'folder not empty',

        self::CODE_4001 => 'goods not found',
        self::CODE_4002 => 'order goods create faild',
        self::CODE_4003 => 'order failed',
        self::CODE_4004 => 'Need to buy',
        self::CODE_4005 => 'pay failed'
    ];
}
