<?php
$str = "abc我想说 我会爱你多一点点";
# base64 对照表
define('Base64Table', 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/');
$a = base64Encode($str);
$b = base64Decode($a);
dd($a);
dd($b);


function base64Decode(string $str64) {
    # base64 字符串长度必然是 4 的倍数; 当然如果想支持不带=的字符串也可以
    if (strlen($str64) % 4) {
        return false;
    }

    $str64 = rtrim($str64, '=');
    $bin_str = '';
    for ($i = 0; $i < strlen($str64); $i++) {
        $key = strpos(Base64Table, $str64[$i]);
        # 超纲
        if ($key === false) {
            return false;
        }
        $bin = decbin($key);
        $bin_str .= str_pad($bin, 6, 0, STR_PAD_LEFT);
    }

    # 确保是 8 的倍数, 不然就删尾部 0
    $remainder = strlen($bin_str) % 8;
    if ($remainder) {
        $bin_str = substr($bin_str, 0, -$remainder);
    }

    $str2 = '';
    $arr = str_split($bin_str, 8);
    foreach ($arr as $byte) {
        $str2 .= chr(bindec($byte));
    }

    return $str2;
}

function base64Encode(string $str) {
    $binLen = 8 * strlen($str);
    $bin = str2bin($str);
    # 补后0 - 确保二进制数字是 6 的倍数
    if ($binLen % 6) {
        $bin .= str_repeat(0, (6 - $binLen % 6));
    }

    $binArr = str_split($bin, 6);
    $str64 = '';
    foreach ($binArr as $b) {
        $key = bindec($b);
        $str64 .= Base64Table[$key];
    }

    $str64Len = strlen($str64);
    # 确保 base64 字符串长度是 4 的倍数，不足则补充 =
    if ($str64Len % 4) {
        $str64 .= str_repeat('=', (4 - $str64Len % 4));
    }

    return $str64;
}

function str2bin($str) {
    $arr = str_split($str, 4);
    $bin_str = '';
    foreach ($arr as $s) {
        # 进制转换是有限制的 不能超过操作系统的位数 按32bit算, 可以转换4个字节; 64bit下可以转换8个字节
        $bin = base_convert(bin2hex($s), 16, 2);
        # 补前 0
        $bin_str .= str_pad($bin, 8 * strlen($s), 0, STR_PAD_LEFT);
    }
    return $bin_str;
}



function dd($str) {
    printf("%s\n", $str);
}
