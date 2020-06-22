<?php

$filename = 'C:\Users\Administrator\Downloads\费玉清 - 夏之旅.wav';

$wavObj = new WavParser($filename);
var_dump($wavObj->getHeader());

class WavParser {

    protected $header = [];

    protected $file_handler;

    public function __construct($file)
    {
        //var_dump(filesize($file));
        $this->file_handler = fopen($file, 'rb');
    }

    public function getHeader()
    {
        if (empty($this->header)) {
            $this->setHeader();
        }
        return $this->header;
    }

    protected function setHeader()
    {
        $header = [];
        $content = fread($this->file_handler, 44);

        $header['ChunkID'] = substr($content, 0, 4);
        // 小端序 - 整型
        $header['ChunkSize'] = $this->str2dec(substr($content, 4, 4), true);
        $header['Format'] = substr($content, 8, 4);
        $header['Subchunk1ID'] = substr($content, 12, 4);
        // 小端序 - 整型
        $header['Subchunk1Size'] = $this->str2dec(substr($content, 16, 4), true);
        // 小端序 - 整型
        $header['AudioFormat'] = $this->str2dec(substr($content, 20, 2), true);
        $header['NumChannels'] = $this->str2dec(substr($content, 22, 2), true);
        $header['SampleRate'] = $this->str2dec(substr($content, 24, 4), true);
        $header['ByteRate'] = $this->str2dec(substr($content, 28, 4), true);
        $header['BlockAlign'] = $this->str2dec(substr($content, 32, 2), true);
        $header['BitsPerSample'] = $this->str2dec(substr($content, 34, 2), true);
        $header['Subchunk2ID'] = substr($content, 36, 4);
        $header['Subchunk2Size'] = $this->str2dec(substr($content, 40, 4), true);

        $this->header = $header;
    }


    /**
     * 字符串转整型 - 可以有多种实现方式
     * @param string $str
     * @param bool $isLittleEndian 是否小端序
     * @return int
     */
    protected function str2dec(string $str, bool $isLittleEndian = false)
    {
        if ($isLittleEndian) {
            $str = strrev($str);
        }
        return hexdec(bin2hex($str));
    }

    /**
     * 字符串转二进制字符串
     * @param string $str
     * @param bool $isLittleEndian 默认大端序
     * @return string
     */
    protected function str2bin(string $str, bool $isLittleEndian = true) {
        $bin_str = '';
        for ($i = 0; $i < strlen($str); $i++) {
            $bin = base_convert(bin2hex($str[$i]), 16, 2);
            # 补前 0
            $bin = str_pad($bin, 8, 0, STR_PAD_LEFT);
            if ($isLittleEndian) {
                $bin_str = $bin . $bin_str;
            } else {
                $bin_str .= $bin;
            }
        }
        return $bin_str;
    }

    public function __destruct()
    {
        if ($this->file_handler) {
            fclose($this->file_handler);
        }
    }
}

