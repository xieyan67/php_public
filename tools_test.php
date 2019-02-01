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
$questionSql = 'SELECT id,qAnswer FROM question where knowledge_name = "样式类"';
$questionRes = $pdo->query($questionSql);
$questionArray = $questionRes->fetchAll(PDO::FETCH_ASSOC);

//获取ht 题库数据
$htQuestionSql = 'select id,qAnswer,qExamineCenter from question_12_11 where qExamineCenter = "样式类"';
$htQuestionRes = $pdo->query($htQuestionSql);
$htQuestionArray = $htQuestionRes->fetchAll(PDO::FETCH_ASSOC);

$insertSql = 'INSERT INTO question_ht_gk(ht_qid,gk_qid,knowledge,qAnswer) VALUE ';
$a = [];
foreach ($htQuestionArray as $htK => $htV){
    foreach ($questionArray as $qK => $qV){
        if($qV['qAnswer'] == $htV['qAnswer']){
            $a[] = $qV;
            $insertSql .= '('.$htV['id'].','.$qV['id'].',"'.$htV['qExamineCenter'].'","'.$htV['qAnswer'].'"),';
        }
    }
}
//var_dump(count($a));
//return;
$insertSql = rtrim($insertSql,',');

//$inserRes = $pdo->exec($insertSql);
if(!$inserRes){
    var_dump($insertSql);
}else{
    var_dump($inserRes);
}
