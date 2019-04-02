<?php
/**
 * Created by PhpStorm.
 * User: lijiangbo
 * Date: 2019-03-19
 * Time: 18:31
 */
use Doctrine\DBAL\DriverManager;
use Phpmig\Adapter;
use Pimple\Container;
use Doctrine\DBAL\Connection;

$container = new Container();

/**
 * @return Connection
 */
$container['db'] = function () {
    return DriverManager::getConnection(array(
        'dbname' => getenv('DB_NAME') ? : 'code-framework',
        'user' => getenv('DB_USER') ? : 'root',
        'password' => getenv('DB_PASSWORD') ? : '',
        'host' => getenv('DB_HOST') ? : '127.0.0.1',
        'port' => getenv('DB_PORT') ? : 3306,
        'driver' => 'pdo_mysql',
        'charset' => 'utf8',
    ));
};

$container['phpmig.adapter'] = function ($c) {
    return new Adapter\Doctrine\DBAL($c['db'], 'migrations');
};

$container['phpmig.migrations_path'] = __DIR__ . '/tests/Fixtures/Migrations';
$container['db']->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

return $container;