<?php
ini_set('date.timezone','Asia/Shanghai');
define('BASE_PATH', __DIR__.'/..');
include __DIR__.'/../config/database.php';

/**
* 排除外的地址
* 不存储于日志系统
*/
// $excludeUrl = require_once(BASE_PATH."/config/excludeUrl.php");

// $registerObj = new \VirgoUtil\Register;

// $factoryObj = new \VirgoUtil\Factory;

// $registerObj->_set("log", $obj);


// var_dump($_GET);
// exit();
// 路由配置
require '../config/routes.php';
