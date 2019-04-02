<?php
/**
 * Created by PhpStorm.
 * User: lijiangbo
 * Date: 2019-03-28
 * Time: 15:04
 */

namespace Code\Framework\User;

use Code\Framework\AppConst;
use Code\Framework\Core\BaseService;
use Code\Framework\User\Strategy\UserStrategy;

class UserService extends BaseService
{
    /**
     * @return UserStrategy
     */
    protected function getUserStr()
    {
        return $this->getApp()->strategy(AppConst::STRATEGY_USER);
    }

    /**
     * @param $id
     * @return array|null
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getUser($id)
    {
        return $this->getUserStr()->getUser($id);
    }

    /**
     * @param $account
     * @return array|null
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getUserByAccount($account)
    {
        return $this->getUserStr()->getUserByAccount($account);
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
        return $this->getUserStr()->searchUsers($conditions, $orderBys, $start, $limit);
    }

    /**
     * 条件查询用户数量
     * @param $conditions
     * @return int
     * @throws \Code\Framework\Exception\DaoException
     */
    public function countUsers($conditions)
    {
        return $this->getUserStr()->countUsers($conditions);
    }

    /**
     * 根据id查询所有用户
     * @param array $userIds
     * @return array
     */
    public function findUsersByIds(array $userIds)
    {
        if (empty($userIds)) {
            return [];
        }

        return $this->getUserStr()->findUsersByIds($userIds);
    }
}