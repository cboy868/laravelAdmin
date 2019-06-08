<?php
/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/2/21
 * Time: 15:12
 */

/**
 * 返回可读性更好的文件尺寸
 */
function human_filesize($bytes, $decimals = 2)
{
    $size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB'];
    $factor = floor((strlen($bytes) - 1) / 3);

    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}


/**
 * 形成树
 * @param $records
 * @return array
 */
function makeTree($records)
{
    $tree = [];
    $records = array_index($records, 'id');
    foreach ($records as &$record) {
        if ($record['pid'] != 0 && isset($records[$record['pid']])) {
            $records[$record['pid']]['sons'][] = &$records[$record['id']];
        } else {
            $tree[] = &$records[$record['id']];
        }
    }
    unset($record);

    return $tree;
}

/**
 * 判断文件的MIME类型是否为图片
 */
function is_image($mimeType)
{
    return starts_with($mimeType, 'image/');
}

/**
 * 生成定长随机数
 * @param $length 随机数长度
 * @return bool|string
 */
function random_str($length, $int = false)
{
    if ($int) {
        $str = '1234567890';
    } else {
        $str = '2346789abcdefghjmnpqrtuxyzABCDEFGHJMNPQRTUXYZ';//62个字符
    }

    $strlen = strlen($str);
    while ($length > $strlen) {
        $str .= $str;
        $strlen += $strlen;
    }
    $str = str_shuffle($str);

    return substr($str, 0, $length);
}

/**
 * 过滤掉数组中值在$filters中的项目
 * @param $array
 * @param $filters
 * @return array
 */
function array_filters($array, $filters = ['null', ''])
{
    return array_filter($array, function ($v) use ($filters) {
        return in_array($v, $filters) ? false : true;
    });
}



/**
 * 二维数组 提key
 * @param $array
 * @param $field
 *
 *
 */
function array_index($array, $field)
{

    if (!is_array($array)) {
        return false;
    }

    $result = [];

    foreach ($array as $item) {
        if (!is_array($item)) {
            $item = (array)$item;
        }
        $result[$item[$field]] = $item;
    }

    return $result;
}
/**
 * 没有代理，获取客户端ip
 * @return array|false|string
 */
//function get_client_ip()
//{
//    $cip = "unknow";
//    if ($_SERVER['REMOTE_ADDR']) {
//        $cip = $_SERVER['REMOTE_ADDR'];
//    } elseif (getenv("REMOTE_ADDR")) {
//        $cip = getenv("REMOTE_ADDR");
//    }
//
//    return $cip;
//}
/**
 * 获取真实client ip
 * @return string
 */
function get_client_ip() {
    if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $ip = getenv('REMOTE_ADDR');
    } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
}