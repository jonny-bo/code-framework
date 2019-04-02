<?php
/**
 * Created by PhpStorm.
 * User: lijiangbo
 * Date: 2019-04-01
 * Time: 10:45
 */

interface UserServiceInterface
{
    /**
     * 根据id查询用户信息
     * @param $userId
     * @return mixed
     */
    public function getUser($userId);

    /**
     * 根据用户账号查询用户信息
     * @param $account
     * @return mixed
     */
    public function getUserByAccount($account);

    /**
     * 根据用户ids查询用户的信息
     * @param array $userIds
     * @return mixed
     */
    public function findUsersByIds(array $userIds);

    /**
     * 条件查询用户的数量
     * @param $conditions
     * @return mixed
     */
    public function countUsers($conditions);

    /**
     * 分页条件查询用户的信息
     * $conditions eg:
     * [
     *      'userId' => $userId //等同于 id = :userId
     *      'account' => $account, //等同于 account = :account
     *      ids' => $ids, //等同于id in (:ids)
     * ]
     *
     * $orderBys eg ['id' => 'DESC', 'account' => 'ASC']
     *
     * @see \Code\Framework\User\Dao\Storage\UserStorage declares() 该方法定义查询条件的构建解析
     *
     * @param $conditions
     * @param $orderBys
     * @param $start
     * @param $limit
     * @return mixed
     */
    public function searchUsers($conditions, $orderBys, $start, $limit);
}