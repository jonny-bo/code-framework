<?php
/**
 * Created by PhpStorm.
 * User: lijiangbo
 * Date: 2019-04-01
 * Time: 18:17
 */

namespace Tests\User;

use Code\Framework\Testing\DatabaseSeeder;

class UserSeeder extends DatabaseSeeder
{
    /**
     * @param bool $isRun
     * @return \Doctrine\Common\Collections\ArrayCollection|mixed
     * @throws \Doctrine\DBAL\DBALException
     */
    public function run($isRun = true)
    {
        $rows = [
            [
                'id' => 1,
                'account' => '15157131726',
                'phone' => '15157131726',
                'nickname' => 'test1',
                'name' => 'test1_name',
                'avatar_img' => 'test1_avatar'
            ],
            [
                'id' => 2,
                'account' => '15157131727',
                'phone' => '15157131727',
                'nickname' => 'test2',
                'name' => 'test2_name',
                'avatar_img' => 'test2_avatar'
            ],
            [
                'id' => 3,
                'account' => '15157131728',
                'phone' => '15157131728',
                'nickname' => 'test3',
                'name' => 'test3_name',
                'avatar_img' => 'test3_avatar'
            ],
        ];

        return $this->insertRows('xm_users_1', $rows, $isRun);
    }
}