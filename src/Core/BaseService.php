<?php
/**
 * Created by PhpStorm.
 * User: lijiangbo
 * Date: 2019-03-28
 * Time: 17:41
 */

namespace Code\Framework\Core;

use Code\Framework\App;

class BaseService
{
    /**
     * @var App
     */
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    protected function getApp()
    {
        return $this->app;
    }
}
