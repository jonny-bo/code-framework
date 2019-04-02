<?php
/**
 * Created by PhpStorm.
 * User: lijiangbo
 * Date: 2019-04-01
 * Time: 13:37
 */

namespace Code\Framework\Core;


use Code\Framework\App;

class BaseStrategy
{
    /**
     * @var App
     */
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * @return App
     */
    protected function getApp()
    {
        return $this->app;
    }
}