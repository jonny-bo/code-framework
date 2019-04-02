<?php
/**
 * Created by PhpStorm.
 * User: lijiangbo
 * Date: 2019-04-01
 * Time: 13:57
 */

namespace Code\Framework\User\Dao\Storage;

use Code\Framework\Core\BaseDb;

class UserStorage extends BaseDb
{
    protected $table = 'xm_users_1';

    public function declares()
    {
        return [
            'conditions' => [
                'id = :userId',
                'account = :account',
                'id IN (:ids)',
                'nickname LIKE :likeNickname'
            ]
        ];
    }

    /**
     * @param $account
     * @return array|null
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getByAccount($account)
    {
        return $this->getByFields([
            'account' => $account
        ]);
    }

    /**
     * @param $ids
     * @return array|mixed[]
     */
    public function findByIds($ids)
    {
        if (empty($ids)) {
            return [];
        }

        return $this->findInField('id', $ids);
    }
}