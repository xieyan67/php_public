<?php
/**
 * Created by PhpStorm.
 * User: shaoshuai
 * Date: 2018/3/13
 * Time: 下午2:47
 */

//连接数据库
$dsn = "mysql:host=123.57.209.247;port=3306;dbname=question-ht-gk;charset=utf8";
$user = 'questionbank';
$pass = 'some_pass';
$pdo = new PDO($dsn, $user, $pass);
//// 2>设置字符集
$pdo->exec('SET NAMES UTF8');
//// 4>执行SQL语句