<?php
/**
 * Created by PhpStorm.
 * User: lijiangbo
 * Date: 2019-04-01
 * Time: 13:36
 */

namespace Code\Framework\User\Strategy;

use Code\Framework\Core\BaseStrategy;
use Code\Framework\User\Dao\Storage\UserStorage;

class UserStrategy extends BaseStrategy
{
    /**
     * @return UserStorage
     */
    protected function getUserStorage()
    {
        return $this->getApp()->storage('User:UserStorage');
    }

    /**
     * @param $userId
     * @return array|null
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getUser($userId)
    {
        return $this->getUserStorage()->get($userId);
    }

    /**
     * 根据account获取用户信息
     * @param $account
     * @return array|null
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getUserByAccount($account)
    {
        return $this->getUserStorage()->getByAccount($account);
    }

    /**
     * 条件分页查询用户信息
     * @param $conditions
     * @param $orderBys
     * @param $start
     * @param $limit
     * @return mixed[]
     * @throws \Code\Framework\Exception\DaoException
     */
    public function searchUsers($conditions, $orderBys, $start, $limit)
    {
        return $this->getUserStorage()->search($conditions, $orderBys, $start, $limit);
    }

    /**
     * 条件查询用户数量
     * @param $conditions
     * @return int
     * @throws \Code\Framework\Exception\DaoException
     */
    public function countUsers($conditions)
    {
        return $this->getUserStorage()->count($conditions);
    }

    /**
     * @param array $userIds
     * @return array
     */
    public function findUsersByIds(array $userIds)
    {
        if (empty($userIds)) {
            return [];
        }

       return $this->getUserStorage()->findByIds($userIds);
    }
}