<?php
/**
 * Created by PhpStorm.
 * User: xieyan
 * Date: 2019/3/26
 * Time: 下午3:08
 */
//教师只读数据库
$mysqlServerName='qbdb.17kgk.com';
$mysqlUsername='questionbank_r';
$mysqlPassword='JustDoIt2015';
$mysqlDatabase='question-bank';

$mysqlTestServerName='123.57.209.247';
$mysqlTestUsername='questionbank';
$mysqlTestPassword='some_pass';
$mysqlTestDatabase='test';

$link=mysqli_connect($mysqlServerName,$mysqlUsername,$mysqlPassword,$mysqlDatabase) or die("error connecting") ;
mysqli_query($link,"set names 'utf8'");


$linkTest=mysqli_connect($mysqlTestServerName,$mysqlTestUsername,$mysqlTestPassword,$mysqlTestDatabase) or die("error connecting") ;
mysqli_query($linkTest,"set names 'utf8'");

if (!$link) {
    printf("Can't connect to MySQL Server. Errorcode: %s ", mysqli_connect_error());
    exit;
}
$i = 1;
$limit = 1000;
while ($i < 3){
    $offset = ($i - 1) * $limit;
    $accountSql = "SELECT id,username,create_time FROM account WHERE status = 1 LIMIT $offset,$limit";
    $results = mysqli_query($link, $accountSql);
    $users = mysqli_fetch_all($results,MYSQLI_ASSOC);
    $insertSql = "INSERT INTO user_active_2017_kgk(uid,username,login_time) VALUE ";

    foreach ($users as $val){

        $insertSql .= '('.$val['id'].',"'.$val['username'].'","'.$val['create_time'].'"),';

    }
    $insertSql = rtrim($insertSql,',');
    mysqli_query($linkTest,$insertSql);
    if(mysqli_query($linkTest,$insertSql)){
        var_dump($offset.'--执行完成！');
    }
    $i++;
}
return;
$limit = 1000;
for ($i = 1; $i < 800;$i++){
    $offset = ($i - 1) * $limit;
    $accountSql = "SELECT id,username,create_time FROM account WHERE status = 1 LIMIT $offset,$limit";
    $results = mysqli_query($link, $accountSql);
    $users = mysqli_fetch_all($results,MYSQLI_ASSOC);

    if(!empty($users)){
        foreach ($users as $val){
            $insertSql = "INSERT INTO user_active_2017_kgk(uid,username,login_time) VALUE ";
            $insertSql .= '('.$val['id'].',"'.$val['username'].'","'.$val['create_time'].'"),';
            $insertSql = rtrim($insertSql,',');
            mysqli_query($linkTest,$insertSql);
            if(!mysqli_query($linkTest,$insertSql)){
                var_dump($val['id'].'--offset='.$offset.'失败');
            }
        }

    }
return;

}
