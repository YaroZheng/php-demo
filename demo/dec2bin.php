<?php
/**
 * 十进制数字转二进制 - 位操作
 * @param int $number
 * @return string
 */
function dec2bin(int $number) {
    $len = 8 * PHP_INT_SIZE;
    $bin_arr = [];

    // 每次 $number 都向右移一位
    for ($i = $len; $i >= 0; $i--, $number >>= 1) {
        // 1 掩码, 取 $number 最后一位, 即二进制0位
        $bin_arr[$i] = $number & 1;
    }

    ksort($bin_arr);
    $bin_str = join('', $bin_arr);

    // return $bin_str; // 完整二进制
    return ltrim($bin_str, '0');
}