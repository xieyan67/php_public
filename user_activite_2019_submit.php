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



$mapArray = [
    1=>[1,
        2,
        4,
        6,
        9,
        11,
        13,
        15,
        17,
        19,
        20,
        21,
        23,
        24,
        25,
        27,
        30,
        31,
        32,
        33,
        43,
        44,
        46,
        49,
        51,
        52,
        53,
        55],
    3=>[3,
        5,
        7,
        10,
        12,
        14,
        16,
        18,
        22,
        26,
        28,
        34,
        35,
        36,
        37,
        38,
        39,
        40,
        41,
        42,
        45,
        47,
        50,
        54]
];


$i = 0;
$count = 1000;

while(true) {

    foreach (range(0, 49) as $num) {
        $startRow = $i * $count;
        $selectSql = "select uid from `order_users_2019` where uid%50 = {$num} order by id";
        $results = mysqli_query($linkTest, $selectSql);
        $result = array_reduce(mysqli_fetch_all($results), function ($result, $value) {
            return array_merge($result, array_values($value));
        }, array());
        $uidArray = implode(',', $result);
        foreach ($mapArray as $key=>$value){
            getUserSubmitStatusBy($num,$uidArray,$link,'2015-01','2015-04',$value,$key,'2015_01_watch_or_submit',$linkTest);
            getUserSubmitStatusBy($num,$uidArray,$link,'2015-04','2015-07',$value,$key,'2015_02_watch_or_submit',$linkTest);
            getUserSubmitStatusBy($num,$uidArray,$link,'2015-07','2015-10',$value,$key,'2015_03_watch_or_submit',$linkTest);
            getUserSubmitStatusBy($num,$uidArray,$link,'2015-10','2016-01',$value,$key,'2015_04_watch_or_submit',$linkTest);
            getUserSubmitStatusBy($num,$uidArray,$link,'2016-01','2016-04',$value,$key,'2016_01_watch_or_submit',$linkTest);
            getUserSubmitStatusBy($num,$uidArray,$link,'2016-04','2016-07',$value,$key,'2016_02_watch_or_submit',$linkTest);
            getUserSubmitStatusBy($num,$uidArray,$link,'2016-07','2016-10',$value,$key,'2016_03_watch_or_submit',$linkTest);
            getUserSubmitStatusBy($num,$uidArray,$link,'2016-10','2017-01',$value,$key,'2016_04_watch_or_submit',$linkTest);
            getUserSubmitStatusBy($num,$uidArray,$link,'2017-01','2017-04',$value,$key,'2017_01_watch_or_submit',$linkTest);
            getUserSubmitStatusBy($num,$uidArray,$link,'2017-04','2017-07',$value,$key,'2017_02_watch_or_submit',$linkTest);
            getUserSubmitStatusBy($num,$uidArray,$link,'2017-07','2017-10',$value,$key,'2017_03_watch_or_submit',$linkTest);
            getUserSubmitStatusBy($num,$uidArray,$link,'2017-10','2018-01',$value,$key,'2017_04_watch_or_submit',$linkTest);
            getUserSubmitStatusBy($num,$uidArray,$link,'2018-01','2018-04',$value,$key,'2018_01_watch_or_submit',$linkTest);
            getUserSubmitStatusBy($num,$uidArray,$link,'2018-04','2018-07',$value,$key,'2018_02_watch_or_submit',$linkTest);
            getUserSubmitStatusBy($num,$uidArray,$link,'2018-07','2018-10',$value,$key,'2018_03_watch_or_submit',$linkTest);
            getUserSubmitStatusBy($num,$uidArray,$link,'2018-10','2019-01',$value,$key,'2018_04_watch_or_submit',$linkTest);
            getUserSubmitStatusBy($num,$uidArray,$link,'2019-01','2019-04',$value,$key,'2019_01_watch_or_submit',$linkTest);
            getUserSubmitStatusBy($num,$uidArray,$link,'2019-04','2019-07',$value,$key,'2019_02_watch_or_submit',$linkTest);
            getUserSubmitStatusBy($num,$uidArray,$link,'2019-07','2019-10',$value,$key,'2019_03_watch_or_submit',$linkTest);
            getUserSubmitStatusBy($num,$uidArray,$link,'2019-10','2020-01',$value,$key,'2019_04_watch_or_submit',$linkTest);

        }
        var_dump($num . '执行完成！！');
        if ($num == 49){
            break 2;
        }
    }

//    foreach ($coursesArray as $key => $item){
//        getUserSubmitStatusBy($uidArray,$link,2015,'2015-01','2015-04',$item,$key,'2015_01_buy',$linkTest);
//        getUserBuyOrderStatusBy($uidArray,$link,2015,'2015-04','2015-07',$item,$key,'2015_02_buy',$linkTest);
//        getUserBuyOrderStatusBy($uidArray,$link,2015,'2015-07','2015-10',$item,$key,'2015_03_buy',$linkTest);
//        getUserBuyOrderStatusBy($uidArray,$link,2015,'2015-10','2016-01',$item,$key,'2015_04_buy',$linkTest);
//        getUserBuyOrderStatusBy($uidArray,$link,2016,'2016-01','2016-04',$item,$key,'2016_01_buy',$linkTest);
//        getUserBuyOrderStatusBy($uidArray,$link,2016,'2016-04','2016-07',$item,$key,'2016_02_buy',$linkTest);
//        getUserBuyOrderStatusBy($uidArray,$link,2016,'2016-07','2016-10',$item,$key,'2016_03_buy',$linkTest);
//        getUserBuyOrderStatusBy($uidArray,$link,2016,'2016-10','2016-01',$item,$key,'2016_04_buy',$linkTest);
//        getUserBuyOrderStatusBy($uidArray,$link,2017,'2017-01','2017-04',$item,$key,'2017_01_buy',$linkTest);
//        getUserBuyOrderStatusBy($uidArray,$link,2017,'2017-04','2017-07',$item,$key,'2017_02_buy',$linkTest);
//        getUserBuyOrderStatusBy($uidArray,$link,2017,'2017-07','2017-10',$item,$key,'2017_03_buy',$linkTest);
//        getUserBuyOrderStatusBy($uidArray,$link,2017,'2017-10','2017-01',$item,$key,'2017_04_buy',$linkTest);
//        getUserBuyOrderStatusBy($uidArray,$link,2018,'2018-01','2018-04',$item,$key,'2018_01_buy',$linkTest);
//        getUserBuyOrderStatusBy($uidArray,$link,2018,'2018-04','2018-07',$item,$key,'2018_02_buy',$linkTest);
//        getUserBuyOrderStatusBy($uidArray,$link,2018,'2018-07','2018-10',$item,$key,'2018_03_buy',$linkTest);
//        getUserBuyOrderStatusBy($uidArray,$link,2018,'2018-10','2018-01',$item,$key,'2018_04_buy',$linkTest);
//        getUserBuyOrderStatusBy($uidArray,$link,2019,'2019-01','2019-04',$item,$key,'2019_01_buy',$linkTest);
//        getUserBuyOrderStatusBy($uidArray,$link,2019,'2019-04','2019-07',$item,$key,'2019_02_buy',$linkTest);
//        getUserBuyOrderStatusBy($uidArray,$link,2019,'2019-07','2019-10',$item,$key,'2019_03_buy',$linkTest);
//        getUserBuyOrderStatusBy($uidArray,$link,2019,'2019-10','2019-01',$item,$key,'2019_04_buy',$linkTest);
//    }

    var_dump($i * $count . '-----' . (($i + 1) * $count) . '执行完成！！');
    var_dump(count($result), $count);
    $i++;

    if (count($result) < $count) {
        break;
    }
}

//function getUserBuyOrderStatusBy($uid,$link,$year,$startTime,$endTime,$courseId,$type,$key,$linkTest){
//    $courseId = implode(',',$courseId);
//
//    if ($year >= 2018){
//        $year = "";
//    }else{
//        $year = '_'.$year;
//    }
//    $selectSql = "select uid from orders".$year." where uid in ({$uid})
//    and object_id in ({$courseId}) and object_type = 1
//    and price >= 3000 and status = 1 and `deleted` = 0 and pay_time is not null and create_time >= '{$startTime}' and create_time < '{$endTime}' ";
//    $result = mysqli_query($link, $selectSql);
//    while ($row = mysqli_fetch_assoc($result)){
//        $updateSql = "update order_users_2019 set
//        {$key} = {$type}
//        where uid = {$row['uid']}";
//        mysqli_query($linkTest,$updateSql);
//    }
//}

function getUserSubmitStatusBy($submitTableNum,$uid,$link,$startTime,$endTime,$mapId,$type,$key,$linkTest){
    $mapId = implode(',',$mapId);
    $selectSql = "select id,uid from submit_logs_{$submitTableNum} where uid in ({$uid}) 
    and mid in ({$mapId}) and 
    create_time >= '{$startTime}' and create_time < '{$endTime}' group by uid HAVING count(id) >= 5";
    $result = mysqli_query($link, $selectSql);
    while ($row = mysqli_fetch_assoc($result)){
        $updateSql = "update order_users_2019 set
        {$key} = {$type}
        where uid = {$row['uid']}";
        mysqli_query($linkTest,$updateSql);
    }
}

mysqli_close($link);