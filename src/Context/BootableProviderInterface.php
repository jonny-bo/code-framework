<?php
/**
 * Created by PhpStorm.
 * User: lijiangbo
 * Date: 2019-03-29
 * Time: 14:15
 */

namespace Code\Framework\Context;

use Code\Framework\App;

interface  BootableProviderInterface
{
    public function boot(App $app);
}