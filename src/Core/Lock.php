<?php
/**
 * Created by PhpStorm.
 * User: lijiangbo
 * Date: 2019-03-29
 * Time: 14:39
 */

namespace Code\Framework\Core;

use Code\Framework\App;
use Doctrine\DBAL\Connection;

/**
 * 数据库锁
 * @deprecated 2.0
 */
class Lock
{
    /**
     * @var App
     */
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * @param $lockName
     * @param int $lockTime
     * @return mixed
     * @throws \Doctrine\DBAL\DBALException
     */
    public function get($lockName, $lockTime = 30)
    {
        $result = $this->getConnection()->fetchAssoc("SELECT GET_LOCK('locker_{$lockName}', {$lockTime}) AS getLock");

        return $result['getLock'];
    }

    /**
     * @param $lockName
     * @return mixed
     * @throws \Doctrine\DBAL\DBALException
     */
    public function release($lockName)
    {
        $result = $this->getConnection()->fetchAssoc("SELECT RELEASE_LOCK('locker_{$lockName}') AS releaseLock");

        return $result['releaseLock'];
    }

    /**
     * @return Connection
     */
    protected function getConnection()
    {
        return $this->app['db'];
    }
}
