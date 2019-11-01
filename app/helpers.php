<?php

if ( ! function_exists('randCode')) {
    function randCode($length = 4)
    {
        $string = '0123456789';
        $len    = strlen($string);
        $str    = '';
        while (strlen($str) < $length) {
            $str .= substr($string, rand(0, 1000000) % $len, 1);
        }

        return $str;
    }
}
