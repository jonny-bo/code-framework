<?php
/**
 * Created by PhpStorm.
 * User: lijiangbo
 * Date: 2019-03-28
 * Time: 17:43
 */

namespace Code\Framework\Context;

use Code\Framework\App;

trait ContainerTarit
{
    /**
     * @var App
     */
    protected $app;

    public function setApp(App $app)
    {
        $this->app = $app;
    }
}