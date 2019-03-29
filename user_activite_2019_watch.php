<?php
/**
 * Created by PhpStorm.
 * User: shaoshuai
 * Date: 2018/3/13
 * Time: 下午2:47
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



$i = 0;
$count = 1000;

while(true) {

    foreach (range(0, 49) as $num) {
        $startRow = $i * $count;
        $selectSql = "select uid from `user_active_2019_kgk` where uid%50 = {$num} order by id";
        $results = mysqli_query($linkTest, $selectSql);
        $result = array_reduce(mysqli_fetch_all($results), function ($result, $value) {
            return array_merge($result, array_values($value));
        }, array());
        $uidArray = implode(',', $result);

        getUserWatchStatusBy($num,$uidArray,$link,'2019-01','2019-02','2019_01_active',$linkTest,$result);
        getUserWatchStatusBy($num,$uidArray,$link,'2019-02','2019-03','2019_02_active',$linkTest,$result);
        getUserWatchStatusBy($num,$uidArray,$link,'2019-03','2019-04','2019_03_active',$linkTest,$result);

        var_dump($num . '执行完成！！');
        if ($num == 49){
            break 2;
        }
    }

    var_dump($i * $count . '-----' . (($i + 1) * $count) . '执行完成！！');
    var_dump(count($result), $count);
    $i++;

    if (count($result) < $count) {
        break;
    }
}

function getUserWatchStatusBy($submitTableNum,$uid,$link,$startTime,$endTime,$key,$linkTest,$uidArr){
    //筛选符合看课
    $selectSql = "select user_id from tc_user_watch_length_log where user_id in ({$uid}) 
        and start_time >= '{$startTime}' and start_time < '{$endTime}'  group by `user_id` HAVING count(id) >= 3";
    $result = mysqli_query($link, $selectSql);
    while ($row = mysqli_fetch_assoc($result)){
        unset($uidArr[array_search($row['user_id'],$uidArr)]);

        $updateSql = "update user_active_2019_kgk set
        {$key} = 1
        where uid = {$row['user_id']} and {$key} = 0";
        mysqli_query($linkTest,$updateSql);
    }

    if(!empty($uidArr)){
        $uid = implode(',',$uidArr);
        //筛选符合刷题
        $selectSql = "select id,uid from submit_logs_{$submitTableNum} where uid in ({$uid})
        and create_time >= '{$startTime}' and create_time < '{$endTime}' group by uid HAVING count(id) >= 3";
        $result = mysqli_query($link, $selectSql);
        while ($row = mysqli_fetch_assoc($result)){
            $updateSql = "update user_active_2019_kgk set
            {$key} = 1
            where uid = {$row['uid']} and {$key} = 0";
            mysqli_query($linkTest,$updateSql);
        }
    }
}

mysqli_close($link);