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

    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) .@$size[$factor];
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
function random_str($length){
    $str = '2346789abcdefghjmnpqrtuxyzABCDEFGHJMNPQRTUXYZ';//62个字符
    $strlen = strlen($str);
    while($length > $strlen){
        $str .= $str;
        $strlen += $strlen;
    }
    $str = str_shuffle($str);
    return substr($str,0, $length);
}