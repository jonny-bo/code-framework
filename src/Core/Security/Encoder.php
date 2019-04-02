<?php
/**
 * Created by PhpStorm.
 * User: lijiangbo
 * Date: 2019-01-30
 * Time: 11:35
 */

namespace Code\Framework\Core\Security;

class Encoder
{
    public static function passwordEncoder($password, $salt)
    {
        return sha1($password.$salt);
    }

    public static function salt()
    {
        return md5(time().rand(100000, 999999));
    }
}
