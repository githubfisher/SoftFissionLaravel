<?php

/**
 * 随机数字验证码
 */
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

/* 隐藏手机号中间几位
 * @param string $mobile 手机号码
 * @param int $num 屏蔽长度
 * @return
 * */
if ( ! function_exists('hidedMobile')) {
    function hidedMobile($mobile)
    {
        return substr($mobile, 0, 3) . '****' . substr($mobile, -4);
    }
}

if ( ! function_exists('str_plural')) {
    function str_plural(string $value, int $count = 2)
    {
        return \Illuminate\Support\Str::plural($value, $count);
    }
}

if ( ! function_exists('snake_case')) {
    function snake_case(string $value, string $delimiter = '_')
    {
        return \Illuminate\Support\Str::snake($value, $delimiter);
    }
}

if ( ! function_exists('str_contains')) {
    function str_contains(string $haystack, $needles)
    {
        return \Illuminate\Support\Str::contains($haystack, $needles);
    }
}
