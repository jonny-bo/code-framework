<?php
/**
 * Created by PhpStorm.
 * User: lijiangbo
 * Date: 2019-03-28
 * Time: 16:24
 */

namespace Tests;

use Code\Framework\App;
use PHPUnit\Framework\TestCase;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class AppTest extends TestCase
{
    public function testConstruct()
    {
        $app = new App();
        $this->assertInstanceOf('Code\Framework\App', $app);

        $config = array(
            'debug' => true,
            'migration.directories' => array('migrations'),
        );

        $app = new App($config);

        $this->assertEquals($config['debug'], $app['debug']);
        $this->assertEquals($config['migration.directories'], $app['migration.directories']);
    }

    public function testRegister()
    {
        $app = new App();
        $app->register(new TestServiceProvider1(), array(
            'test_1.options' => array(
                'option1' => 'option1_value',
                'option2' => 'option2',
            ),
        ));

        $this->assertEquals('test_1', $app['test_1']);
        $this->assertEquals('option1_value', $app['test_1.options']['option1']);
        $this->assertEquals('option2', $app['test_1.options']['option2']);
    }

    public function testService()
    {
        $app = new App();
        $app['autoload.aliases']['Example'] = 'Tests\Example';

        $service = $app->service('Example:ExampleService');
        $this->assertInstanceOf('Tests\Example\ExampleService', $service);
        $this->assertEquals($service, $app['@Example:ExampleService']);

        $app = new App();
        $app['autoload.aliases']['Example'] = 'Tests\\Example';

        $service1 = $app->service('Example:ExampleService');
        $service2 = $app->service('Example:ExampleService');

        $this->assertEquals($service1, $service2);
    }
}

class TestServiceProvider1 implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['test_1'] = 'test_1';
    }
}