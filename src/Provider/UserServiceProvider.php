<?php

namespace Code\Framework\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class UserServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['autoload.aliases']['User'] = 'Code\Framework\User';
    }
}
