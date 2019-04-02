<?php
/**
 * Created by PhpStorm.
 * User: lijiangbo
 * Date: 2019-04-01
 * Time: 18:15
 */

namespace Tests\User;

use Code\Framework\User\UserService;
use Tests\IntegrationTestCase;

class UserServiceTest extends IntegrationTestCase
{
    public function testGetUser()
    {
        $this->seed(UserSeeder::class);

        $user = $this->getUserService()->getUser(1);

        $this->assertTrue(is_array($user));
        $this->assertEquals(1, $user['id']);
        $this->assertEquals('15157131726', $user['account']);
        $this->assertEquals('15157131726', $user['phone']);
        $this->assertEquals('test1', $user['nickname']);
    }

    public function testSerachUsers()
    {
        $this->seed(UserSeeder::class);

        //测试根据id查询
        $conditions = [
            'userId' => 1
        ];

        $users = $this->getUserService()->searchUsers($conditions, [], 0, 10);
        $this->assertTrue(is_array($users));
        $this->assertTrue(is_array($users[0]));
        $this->assertEquals(1, $users[0]['id']);

        //测试根据手机号查询
        $conditions = [
            'account' => '15157131726'
        ];

        $users = $this->getUserService()->searchUsers($conditions, [], 0, 10);
        $this->assertTrue(is_array($users));
        $this->assertTrue(is_array($users[0]));
        $this->assertEquals('15157131726', $users[0]['account']);

        //测试倒叙排序和in查询
        $conditions = [
            'ids' => [1, 2, 3]
        ];

        $users = $this->getUserService()->searchUsers($conditions, ['id' => 'DESC'], 0, 10);
        $this->assertTrue(is_array($users));
        $this->assertEquals(3, count($users));
        $this->assertEquals(3,$users[0]['id']);

        //测试模糊匹配昵称
        $conditions = [
            'likeNickname' => 'test1'
        ];

        $users = $this->getUserService()->searchUsers($conditions, [], 0, 10);
        $this->assertTrue(is_array($users));
        $this->assertTrue(is_array($users[0]));
        $this->assertEquals(1, $users[0]['id']);
    }

    public function testCountUsers()
    {
        $this->seed(UserSeeder::class);

        $conditions = [
            'userId' => 1
        ];
        $count = $this->getUserService()->countUsers($conditions);
        $this->assertEquals(1, $count);

        //测试根据手机号查询
        $conditions = [
            'account' => '15157131726'
        ];
        $count = $this->getUserService()->countUsers($conditions);
        $this->assertEquals(1, $count);

        $conditions = [
            'ids' => [1, 2, 3]
        ];
        $count = $this->getUserService()->countUsers($conditions);
        $this->assertEquals(3, $count);

        //测试模糊匹配昵称
        $conditions = [
            'likeNickname' => 'test1'
        ];
        $count = $this->getUserService()->countUsers($conditions);
        $this->assertEquals(1, $count);
    }

    public function testGetUserByAccount()
    {
        $this->seed(UserSeeder::class);

        $user = $this->getUserService()->getUserByAccount('15157131726');

        $this->assertTrue(is_array($user));
        $this->assertEquals(1, $user['id']);
        $this->assertEquals('15157131726', $user['account']);
        $this->assertEquals('15157131726', $user['phone']);
        $this->assertEquals('test1', $user['nickname']);
    }

    public function testFindUsersByIds()
    {
        $this->seed(UserSeeder::class);

        $users = $this->getUserService()->findUsersByIds([1, 2, 3]);

        $this->assertTrue(is_array($users));
        $this->assertEquals(3, count($users));
    }

    /**
     * @return UserService
     */
    private function getUserService()
    {
        return $this->app->service('User:UserService');
    }
}