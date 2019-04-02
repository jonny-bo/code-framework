<?php
/**
 * Created by PhpStorm.
 * User: lijiangbo
 * Date: 2019-04-01
 * Time: 14:19
 */
namespace Code\Framework\Core;

interface DbInterface
{
    public function table();

    public function declares();
}