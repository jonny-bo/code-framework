<?php
/**
 * 应用服务层依赖注入容器App
 * User: lijiangbo
 * Date: 2019-03-28
 * Time: 14:17
 */

namespace Code\Framework;

use Code\Framework\Context\BootableProviderInterface;
use Code\Framework\Context\ContainerAutoloader;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class App extends Container
{
    protected $providers = array();
    protected $booted = false;

    public function __construct(array $values = array())
    {
        $app = $this;
        $app['debug'] = false;
        $app['logger'] = null;

        $app['migration.directories'] = new \ArrayObject();
        $app['autoload.aliases'] = new \ArrayObject(array('' => 'App'));

        $app['autoload.service_proxy'] = function ($app) {
            return function ($namespace, $name) use ($app) {
                $className = "{$namespace}\\{$name}";

                return new $className($app);
            };
        };

        $app['autoload.strategy_proxy'] = function ($app) {
            return function ($namespace, $name) use ($app) {
                $className = "{$namespace}\\Strategy\\{$name}";

                return new $className($app);
            };
        };

        $app['autoload.storage_proxy'] = function ($app) {
            return function ($namespace, $name) use ($app) {
                $className = "{$namespace}\\Dao\\Storage\\{$name}";

                return new $className($app);
            };
        };

        $app['autoload.cache_proxy'] = function ($app) {
            return function ($namespace, $name) use ($app) {
                $className = "{$namespace}\\Dao\\Cache\\{$name}";

                return new $className($app);
            };
        };

        $app['autoloader'] = function ($app) {
            return new ContainerAutoloader(
                $app,
                $app['autoload.aliases'],
                array(
                    'service' => $app['autoload.service_proxy'],
                    'cache' => $app['autoload.cache_proxy'],
                    'storage' => $app['autoload.storage_proxy'],
                    'strategy' => $app['autoload.strategy_proxy'],
                )
            );
        };

        parent::__construct($values);
    }

    public function boot()
    {
        if (true === $this->booted) {
            return;
        }

        foreach ($this->providers as $provider) {
            if ($provider instanceof BootableProviderInterface) {
                $provider->boot($this);
            }
        }

        $this->booted = true;
    }

    /**
     * 注册服务
     * @param ServiceProviderInterface $provider
     * @param array $values
     * @return $this|Container
     */
    public function register(ServiceProviderInterface $provider, array $values = array())
    {
        $this->providers[] = $provider;
        parent::register($provider, $values);

        return $this;
    }

    /**
     * 获取服务层
     * @param $alias
     * @return mixed
     */
    public function service($alias)
    {
        return $this['autoloader']->autoload('service', $alias);
    }

    /**
     * 获取strategy层
     * @param $alias
     * @return mixed
     */
    public function strategy($alias)
    {
        return $this['autoloader']->autoload('strategy', $alias);
    }

    /**
     * 获取storage层
     * @param $alias
     * @return mixed
     */
    public function storage($alias)
    {
        return $this['autoloader']->autoload('storage', $alias);
    }

    /**
     * 获取cache层
     * @param $alias
     * @return mixed
     */
    public function cache($alias)
    {
        return $this['autoloader']->autoload('cache', $alias);
    }
}
