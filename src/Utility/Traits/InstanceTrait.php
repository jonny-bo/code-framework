<?php
/**
 * Created by PhpStorm.
 * User: lijiangbo
 * Date: 2019-02-19
 * Time: 16:22
 */

namespace Code\Framework\Utility\Traits;

trait InstanceTrait
{
    /**
     * @var self
     */
    protected static $singleton;

    /**
     * @param mixed ...$arguments
     * @return InstanceTrait|object
     * @throws \ReflectionException
     */
    public static function getSingleton(...$arguments)
    {
        if (self::$singleton && self::$singleton instanceof static) {
            return self::$singleton;
        }
        return self::$singleton = self::getInstance(...$arguments);
    }


    /**
     * @param mixed ...$arguments
     * @return object
     * @throws \ReflectionException
     */
    public static function getInstance(...$arguments)
    {
        $reflection = new \ReflectionClass(static::class);
        if (count($arguments) == 1 && is_array($arguments[0])) {
            $config = $arguments[0];
            $arguments = [];
            foreach ($reflection->getConstructor()->getParameters() as $parameter) {
                if (isset($config[$parameter->getName()]) && $parameterValue = $config[$parameter->getName()]) {
                    $arguments[] = $parameterValue;
                } elseif ($parameter->isDefaultValueAvailable() && $defaultValue = $parameter->getDefaultValue()) {
                    $arguments[] = $defaultValue;
                } else {
                    $arguments[] = null;
                }
            }
        }
        return $reflection->newInstanceArgs($arguments);
    }
}
