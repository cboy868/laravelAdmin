<?php

namespace App\Common;

/**
 *
 * @author: wansq
 * @since: 1.0
 * Date: 2019/6/15
 * Time: 11:51
 */

class StringHelper
{
    /**
     * Safely casts a float to string independent of the current locale.
     *
     * The decimal separator will always be `.`.
     * @param float|int $number a floating point number or integer.
     * @return string the string representation of the number.
     * @since 2.0.13
     */
    public static function floatToString($number)
    {
        // . and , are the only decimal separators known in ICU data,
        // so its safe to call str_replace here
        return str_replace(',', '.', (string) $number);
    }
}