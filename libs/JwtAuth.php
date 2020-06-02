<?php


namespace libs;


class JwtAuth
{
    public static $supported_algs = array(
        'ES256' => array('openssl', 'SHA256'),
        'HS256' => array('hash_hmac', 'SHA256'),
        'HS384' => array('hash_hmac', 'SHA384'),
        'HS512' => array('hash_hmac', 'SHA512'),
        'RS256' => array('openssl', 'SHA256'),
        'RS384' => array('openssl', 'SHA384'),
        'RS512' => array('openssl', 'SHA512'),
    );

    public static function encode(array $payload, string $key, string $alo = 'HS256')
    {
        $header = [
            'tye' => 'JWT',
            'alo' => $alo
        ];
        $segments = [];
        $segments[] = self::urlBase64Encode(self::jsonEncode($header));
        $segments[] = self::urlBase64Encode(self::jsonEncode($payload));

        $signing_input = implode('.', $segments);
    }

    public static function sign(string $msg, string $key, string $alg = 'HS254')
    {
        if (empty(static::$supported_algs[$alg])) {
            throw new \DomainException("Algorithm not supported");
        }
        list($function, $algorithm) = static::$supported_algs[$alg];

        switch ($function) {
            case 'hash_hmac':
                return hash_hmac($algorithm, $msg, $key, true);
            case 'openssl':

        }

    }

    public static function jsonEncode($input)
    {
        $json = json_encode($input);
        if (empty($json)) {
            throw new \DomainException("json 格式错误");
        }
        return $json;
    }

    public static function urlsafeB64Decode($input)
    {
        $remainder = \strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= \str_repeat('=', $padlen);
        }
        return \base64_decode(\strtr($input, '-_', '+/'));
    }

    public static function urlBase64Encode(string $input)
    {
        return rtrim(strtr(base64_encode($input), '+/', '-_'), '=');
    }
}