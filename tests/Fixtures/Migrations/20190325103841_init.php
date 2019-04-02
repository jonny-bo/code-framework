<?php

use Phpmig\Migration\Migration;

class Init extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = /** @lang text */
            "
            CREATE TABLE IF NOT EXISTS `xm_users` (
              `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
              `account` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
              `phone` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号',
              `password` varchar(64) NOT NULL DEFAULT '' COMMENT '密码',
              `salt` varchar(32) NOT NULL DEFAULT '' COMMENT '密码盐值',
              `nickname` varchar(255) NOT NULL DEFAULT '' COMMENT '昵称',
              `name` varchar(128) NOT NULL DEFAULT '' COMMENT '真实姓名',
              `avatar_img` varchar(255) DEFAULT '' COMMENT '头像图片地址',
              `email` varchar(64) NOT NULL DEFAULT '' COMMENT '邮箱',
              `sex` tinyint(4) UNSIGNED DEFAULT '0' COMMENT '性别：1男2女0未设置3保密',
              `school_id` int(11) UNSIGNED DEFAULT '0' COMMENT '所属学校的id',
              `xmschool_id` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '小码校区的id',
              `autograph` varchar(255) DEFAULT '' COMMENT '个人签名',
              `age` int(11) UNSIGNED DEFAULT '0' COMMENT '年龄（生日 针对用户自己填的）',
              `province_code` int(11) UNSIGNED DEFAULT '0' COMMENT '省份code',
              `city_code` int(11) UNSIGNED DEFAULT '0' COMMENT '市级code',
              `area_code` int(11) UNSIGNED DEFAULT '0' COMMENT '区域code',
              `os_from`tinyint(4) UNSIGNED DEFAULT '0' COMMENT '平台来源',
              `add_time` int(11) UNSIGNED NOT NULL DEFAULT '0',
              `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
              `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态：默认1，1为正常0为禁用可恢复，-1为删除',
              PRIMARY KEY (`id`),
              UNIQUE KEY `account` (`account`),
              KEY `phone_index` (`phone`) USING BTREE,
              KEY `nickname_index` (`nickname`) USING BTREE,
              KEY `name_index` (`name`) USING BTREE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='前台用户总表';
            
            CREATE TABLE IF NOT EXISTS `xm_user_auths` (
              `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
              `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
              `identity_type` tinyint(4) DEFAULT '0' COMMENT '登录类型，第三方应用名称（微信 微博等）',
              `identifier` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '标识（第三方应用的唯一标识）',
              `credential` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '密码凭证（站外的不保存或保存token）',
              `refresh_credential` varchar(255) CHARACTER SET utf8 DEFAULT '' COMMENT '用于刷新密码凭证）',
              `expired_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '凭证过期时间，默认为0不过期',
              `auth_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '授权时间',
              PRIMARY KEY (`id`),
              KEY `k_user_id` (`user_id`) USING BTREE,
              KEY `k_identifier` (`identifier`) USING BTREE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户认证表';
            
            CREATE TABLE IF NOT EXISTS `xm_user_register` (
              `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
              `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
              `reg_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '注册时间',
              `reg_ip` varchar(32) NOT NULL DEFAULT '' COMMENT '注册ip',
              `reg_phone` varchar(32) NOT NULL DEFAULT '' COMMENT '注册手机号',
              `data` varchar(255) NOT NULL DEFAULT '' COMMENT '其他注册信息',
              PRIMARY KEY (`id`),
              KEY `k_user_id` (`user_id`) USING BTREE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户注册信息表';
            
            CREATE TABLE `xm_user_login_log` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键自增',
              `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
              `last_login_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后的登陆时间',
              `login_app` varchar(64) NOT NULL DEFAULT '' COMMENT '登录的应用平台',
              `from` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT '来源：1为web2为android3为ios',
              `last_login_ip` varchar(16) NOT NULL DEFAULT '' COMMENT '最后登录的Ip',
              `device_token` varchar(255) NOT NULL DEFAULT '' COMMENT '登录的设备唯一标识',
              `count` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '登录的次数',
              PRIMARY KEY (`id`),
              KEY `k_user_id` (`user_id`) USING BTREE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='登陆记录表';
        ";
        $container = $this->getContainer();
        $container['db']->exec($sql);
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $sql = /** @lang text */
            "
                DROP TABLE IF EXISTS `xm_users`;
                DROP TABLE IF EXISTS `xm_user_auths`;
                DROP TABLE IF EXISTS `xm_user_register`;
                DROP TABLE IF EXISTS `xm_user_login_log`;
            ";
        $container = $this->getContainer();
        $db = $container['db'];
        $db->exec($sql);
    }
}
