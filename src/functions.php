<?php
/**
 * Created by PhpStorm.
 * User: lijiangbo
 * Date: 2019-03-20
 * Time: 09:52
 */


if (!function_exists('env')) {
    /**
     * @param $key
     * @param null $default
     * @return array|bool|false|string|null
     */
    function env($key, $default = null)
    {
        return \Code\Framework\Utility\Env::get($key, $default);
    }
}
