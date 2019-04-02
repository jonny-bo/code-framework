<?php

namespace Tests;

use Code\Framework\App;
use Code\Framework\Provider\DoctrineServiceProvider;
use Code\Framework\Provider\UserServiceProvider;
use Code\Framework\Testing\DatabaseSeeder;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use PHPUnit\Framework\TestCase;
use Mockery;

class IntegrationTestCase extends TestCase
{
    /**
     * @var \Composer\Autoload\ClassLoader
     */
    public static $classLoader = null;

    /**
     * @var App
     */
    protected $app;

    /**
     * @var Connection
     */
    protected $db;

    /**
     * @var \Redis|\RedisArray
     */
    protected $redis;

    public function setUp()
    {
        $this->app = $this->createApp();
        $this->db = $this->app['db'];

        //$this->redis = $this->app['redis'];

        $this->db->beginTransaction();
        //$this->redis->flushDB();
    }

    /**
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function tearDown()
    {
        $this->db->rollBack();
        //$this->redis->close();

        unset($this->db);
        //unset($this->redis);
        unset($this->biz);
    }

    /**
     * 用于 mock　service，strategy, stroage, cache　等
     * 如　$this->mockObjectIntoApp(
     *      'User:UserService',
     *       array(
     *          array(
     *              'functionName' => 'getUser',
     *              'returnValue' => array('id' => 1),
     *          ),
     *      )
     *  );
     * ＠param $alias  别名 User:UserService => (User/UserService)
     * ＠param $params 二维数组
     *  array(
     *      array(
     *          'functionName' => 'getUser',　//必填
     *          'returnValue' => array('id' => 1),　// 非必填，填了表示有相应的返回结果
     *          'throwException' => new \Exception(), //object Exception or string Exception ，和returnValue 只能二选一，否则throwException优先
     *          'withParams' => array('param1', array('arrayParamKey1' => '123')),　
     *                          //非必填，表示填了相应参数才会有相应返回结果
     *                          //参数必须要用一个数组包含
     *          'runTimes' => 1 //非必填，表示跑第几次会出相应结果, 不填表示无论跑多少此，结果都一样
     *      )
     *  )
     * @param $alias
     * @param array $params
     * @return \Mockery\MockInterface
     */
    protected function mockObjectIntoApp($alias, $params = array())
    {
        $aliasList = explode(':', $alias);
        $className = end($aliasList);
        $mockObj = Mockery::mock($className);

        foreach ($params as $param) {
            $expectation = $mockObj->shouldReceive($param['functionName']);

            if (!empty($param['runTimes'])) {
                $expectation = $expectation->times($param['runTimes']);
            }

            if (!empty($param['withParams'])) {
                $expectation = $expectation->withArgs($param['withParams']);
            } else {
                $expectation = $expectation->withAnyArgs();
            }

            if (!empty($param['returnValue'])) {
                $expectation->andReturn($param['returnValue']);
            }

            if (!empty($param['andReturnValues'])) {
                $expectation->andReturnValues($param['andReturnValues']);
            }

            if (!empty($param['throwException'])) {
                $expectation->andThrow($param['throwException']);
            }
        }

        $this->app['@'.$alias] = $mockObj;

        return $mockObj;
    }

    protected function createApp(array $options = array())
    {
        $defaultOptions = array(
            'db.options' => array(
                'dbname' => getenv('DB_NAME') ?: 'code-framework-test',
                'user' => getenv('DB_USER') ?: 'root',
                'password' => getenv('DB_PASSWORD') ?: '',
                'host' => getenv('DB_HOST') ?: '127.0.0.1',
                'port' => getenv('DB_PORT') ?: 3306,
                'driver' => 'pdo_mysql',
                'charset' => 'utf8',
            ),
            'redis.options' => array(
                'host' => getenv('REDIS_HOST') ?: '127.0.0.1',
            ),
            'debug' => true,
        );
        $options = array_merge($defaultOptions, $options);

        $app = new App($options);
        $app['autoload.aliases']['Example'] = 'Tests\\Example';

        $app->register(new DoctrineServiceProvider());
        $app->register(new UserServiceProvider());

        return $app;
    }

    /**
     * @param string $seeder
     * @param bool   $isRun
     *
     * @return ArrayCollection
     */
    protected function seed($seeder, $isRun = true)
    {
        /* @var $seeder DatabaseSeeder */
        $seeder = new $seeder($this->db);

        return $seeder->run($isRun);
    }

    protected function grabAllFromDatabase($table, $column, array $criteria = array())
    {
    }

    protected function grabFromDatabase($table, $column, array $criteria = array())
    {
    }

    protected function fetchFromDatabase($table, array $criteria = array())
    {
        /* @var $builder QueryBuilder */
        $builder = $this->app['db']->createQueryBuilder();
        $builder->select('*')->from($table);

        $index = 0;
        foreach ($criteria as $key => $value) {
            $builder->andWhere("{$key} = ?");
            $builder->setParameter($index, $value);
            ++$index;
        }

        return $builder->execute()->fetch(\PDO::FETCH_ASSOC);
    }

    protected function fetchAllFromDatabase($table, array $criteria = array())
    {
        /* @var $builder QueryBuilder */
        $builder = $this->app['db']->createQueryBuilder();
        $builder->select('*')->from($table);

        $index = 0;
        foreach ($criteria as $key => $value) {
            $builder->andWhere("{$key} = ?");
            $builder->setParameter($index, $value);
            ++$index;
        }

        return $builder->execute()->fetchAll(\PDO::FETCH_ASSOC);
    }
}
