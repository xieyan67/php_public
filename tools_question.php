<?php
/**
 * Created by PhpStorm.
 * User: xieyan
 * Date: 2019/1/23
 * Time: 下午5:24
 */
//连接数据库
$dsn = "mysql:host=123.57.209.247;port=3306;dbname=question-ht-gk;charset=utf8";
$user = 'questionbank';
$pass = 'some_pass';
$pdo = new PDO($dsn, $user, $pass);
//// 2>设置字符集
$pdo->exec('SET NAMES UTF8');
//// 4>执行SQL语句
/// 获取公考题库数据
$questionSql = 'SELECT id,answer FROM question';
$questionRes = $pdo->query($questionSql);
$questionArray = $questionRes->fetchAll(PDO::FETCH_ASSOC);


foreach ($questionArray as $value){
    $answer = json_decode($value['answer'],true);
    $qAnswer = isset($answer['answer']) ? $answer['answer'] : null;
//    var_dump($qAnswer);
    $upSql = "UPDATE question set qAnswer = '".$qAnswer ."' where id = ".$value['id'];
//    $upRes = $pdo->exec($upSql);

}

