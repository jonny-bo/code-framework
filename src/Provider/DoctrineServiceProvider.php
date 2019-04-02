<?php

/*
 * 此文件来自 Silex 项目(https://github.com/silexphp/Silex).
 *
 * 版权信息请看 LICENSE.SILEX
 */

namespace Code\Framework\Provider;

use Code\Framework\Core\Lock;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Configuration;
use Doctrine\Common\EventManager;

/**
 * Doctrine DBAL Provider.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class DoctrineServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $app
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function register(Container $app)
    {
        $app['db.default_options'] = array(
            'driver' => 'pdo_mysql',
            'dbname' => null,
            'host' => 'localhost',
            'user' => 'root',
            'password' => null,
        );

        $app['dbs.options.initializer'] = $app->protect(function () use ($app) {
            static $initialized = false;

            if ($initialized) {
                return;
            }

            $initialized = true;

            if (!isset($app['dbs.options'])) {
                $app['dbs.options'] = array('default' => isset($app['db.options']) ? $app['db.options'] : array());
            }

            $tmp = $app['dbs.options'];
            foreach ($tmp as $name => &$options) {
                $options = array_replace($app['db.default_options'], $options);

                if (!isset($app['dbs.default'])) {
                    $app['dbs.default'] = $name;
                }
            }
            $app['dbs.options'] = $tmp;
        });

        $app['dbs'] = function ($app) {
            $app['dbs.options.initializer']();

            $dbs = new Container();
            foreach ($app['dbs.options'] as $name => $options) {
                if ($app['dbs.default'] === $name) {
                    // we use shortcuts here in case the default has been overridden
                    $config = $app['db.config'];
                    $manager = $app['db.event_manager'];
                } else {
                    $config = $app['dbs.config'][$name];
                    $manager = $app['dbs.event_manager'][$name];
                }

                $dbs[$name] = function () use ($options, $config, $manager) {
                    return DriverManager::getConnection($options, $config, $manager);
                };
            }

            return $dbs;
        };

        $app['dbs.config'] = function ($app) {
            $app['dbs.options.initializer']();

            $configs = new Container();

            foreach ($app['dbs.options'] as $name => $options) {
                $configs[$name] = new Configuration();
            }

            return $configs;
        };

        $app['dbs.event_manager'] = function ($app) {
            $app['dbs.options.initializer']();

            $managers = new Container();
            foreach ($app['dbs.options'] as $name => $options) {
                $managers[$name] = new EventManager();
            }

            return $managers;
        };

        $this->registerShortcutForFirstDb($app);
        $this->registerLock($app);
    }

    private function registerShortcutForFirstDb($app)
    {
        $app['db'] = function ($app) {
            $dbs = $app['dbs'];
            return $dbs[$app['dbs.default']];
        };

        $app['db.config'] = function ($app) {
            $dbs = $app['dbs.config'];

            return $dbs[$app['dbs.default']];
        };

        $app['db.event_manager'] = function ($app) {
            $dbs = $app['dbs.event_manager'];

            return $dbs[$app['dbs.default']];
        };
    }

    private function registerLock($app)
    {
        $app['lock'] = function ($app) {
            return new Lock($app);
        };
    }
}