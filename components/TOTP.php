<?php

class TOTP
{
    public static function generate($key = 'WHDQ9I4W5FZSCCI0',$timestamp, $interval = 60)
    {
        $hmac = hash_hmac('sha1', floor($timestamp / $interval), $key);
        $otp = self::truncate($hmac, 6);
        return $otp;
    }

    public static function generateTest($key = 'WHDQ9I4W5FZSCCI0',$message, $interval = 60)
    {
        //$message = floor($timestamp / $interval);
        $hmac = hash_hmac('sha1', $message, $key);
        $otp = self::truncate($hmac, 6);
        return $otp;
    }

    public static function verifyTest($pass, $key = 'WHDQ9I4W5FZSCCI0', $message, $interval = 60)
    {
        if (self::generateTest($key, $message, $interval) == $pass) return true;
        if (self::generateTest($key,$message-1, $interval) == $pass) return true;
        if (self::generateTest($key,$message+1, $interval) == $pass) return true;
        return false;
    }

    public static function hmac($interval = 30) //ненужное говно видимо
    {
        return floor(time() / 30);
    }

    public static function truncate($hash, $digits = 6)
    {
        $lastNum = substr($hash,-1);
        $lastNum = base_convert($lastNum, 16, 10);
        $offset = 2 * $lastNum;
        $otp = substr($hash, $offset, 8);
        $otp = base_convert($otp, 16, 10);
        return $otp = substr($otp, -$digits);
    }


    public static function verify($pass, $key = 'WHDQ9I4W5FZSCCI0', $interval = 60)
    {
        if (self::generate($key, time(), $interval) == $pass) return true;
        if (self::generate($key,time()-60, $interval) == $pass) return true;
        if (self::generate($key,time()+60, $interval) == $pass) return true;
        return false;
    }

}