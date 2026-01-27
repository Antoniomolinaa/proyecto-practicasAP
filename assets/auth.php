<?php

include_once "jwt/BeforeValidException.php";
include_once "jwt/ExpiredException.php";
include_once "jwt/SignatureInvalidException.php";


use Firebase\JWT\JWT;

class Auth
{
    private static $secret_key = 'gcQBdWHl%K2*N@*s$cO@f9';
    private static $encrypt = ['HS256'];
    private static $aud = null;

    public static function SignIn($data)
    {
        $time = time();

        $token = array(
            'exp' => $time + (60*60*6000),
            'aud' => self::Aud(),
            'data' => $data
        );

        return JWT::encode($token, self::$secret_key);
    }

    public static function Check($token)
    {
        if(empty($token))
        {
            return false;
        }

        try {
                $decode = JWT::decode(
                $token,
                self::$secret_key,
                self::$encrypt
                );
        } catch (\Exception $e) {
            return false;
        }

        if($decode->exp < time())
            return false; 

              

        if($decode->aud !== self::Aud())
        {
            return false;
        }

        return true;
    }

    public static function GetData($token)
    {
        return JWT::decode(
            $token,
            self::$secret_key,
            self::$encrypt
        )->data;
    }

    private static function Aud()
    {
        $aud = '';

        $aud .= "proyecter";

        return sha1($aud);
    }
}
?>