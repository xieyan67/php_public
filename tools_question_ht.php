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
$questionSql = 'SELECT id,qTitle FROM question_12_11 where qTitle like "%?imageView2%"';
$questionRes = $pdo->query($questionSql);
$questionArray = $questionRes->fetchAll(PDO::FETCH_ASSOC);
var_dump(join(',',array_column($questionArray,'id')));
return;
foreach ($questionArray as $value){
    $reg2 = '/(\?imageView2\/)+\w+[\w\/\.\-]*(jpg|gif|png)/i';
    $str = preg_replace($reg2,'',$value['qTitle']);

    $upSql = "UPDATE question_12_11 set qTitle = '".$str ."' where id = ".$value['id'];
    $upRes = $pdo->exec($upSql);
    if($upRes){
        var_dump($upRes);
    }else{
        var_dump($upSql);
    }
}


