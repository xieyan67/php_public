<?php
/**
 * Created by PhpStorm.
 * User: xieyan
 * Date: 2019/1/23
 * Time: 下午5:24
 */
//教师只读数据库
$mysqlServerName='qbdb.17kjs.com';
$mysqlUsername='questionbank_r';
$mysqlPassword='JustDoIt2015';
$mysqlDatabase='question-bank';

$mysqlTestServerName='123.57.209.247';
$mysqlTestUsername='questionbank';
$mysqlTestPassword='some_pass';
$mysqlTestDatabase='test';

//连接数据库
$link = mysqli_connect($mysqlServerName,$mysqlUsername,$mysqlPassword,$mysqlDatabase) or die("error connecting") ;
mysqli_query($link,"set names 'utf8'");

$linkTest = mysqli_connect($mysqlTestServerName,$mysqlTestUsername,$mysqlTestPassword,$mysqlTestDatabase) or die("error connecting") ;
mysqli_query($linkTest,"set names 'utf8'");

//获取 购买课程的用户信息

//$courseId = [2421,2420,2419,2728,2729,2730,2917,2918,2919];//18年一季度 考证笔试（科目一/二）
//$courseId = [5500,5501,5502,5595,5597,5598,5668,5669,5673,5690,5691,5692];//19年一季度 考证笔试（科目一/二）
//$courseId = [1912,1915];//18年一季度  考证笔试（科目三）
//$courseId = [4776,4777,4778,4779];//19年一季度  考证笔试（科目三）
//$courseId = [2184,2265,2566,2742,2885,3030,1660,3093,3278];//18年第一季度  考编公基
//$courseId = [4182,5377,5534,5638,5685,5800,5948,6120];//19年第一季度  考编公基
//$courseId = [3285,3066,3007,2724,2128];//18年第一季度 考编幼教


$courses = [
    [
        'ids' => '1912,1915',
        'courses_product' => '考证笔试（科目三）',
        'courses_point' => '18年一季度',
    ],
    [
        'ids' => '4776,4777,4778,4779',
        'courses_product' => '考证笔试（科目三）',
        'courses_point' => '19年一季度',
    ],
    [
        'ids' => '2184,2265,2566,2742,2885,3030,1660,3093,3278',
        'courses_product' => '考编公基',
        'courses_point' => '18年第一季度',
    ],
    [
        'ids' => '4182,5377,5534,5638,5685,5800,5948,6120',
        'courses_product' => '考编公基',
        'courses_point' => '19年一季度',
    ],
    [
        'ids' => '3285,3066,3007,2724,2128',
        'courses_product' => '考编幼教',
        'courses_point' => '18年一季度',
    ],
    [
        'ids' => '6081,5891,5717,5697,5565,5359',
        'courses_product' => '考编幼教',
        'courses_point' => '19年一季度',
    ],
];




foreach ($courses as $val){
    saveUserOrders($val['ids'],$val['courses_product'],$val['courses_point'],$linkTest,$link);
    break;
}

function saveUserOrders($cids,$coursesProduct,$coursesPoint,$linkTest,$link){
    $num = 2000;
    $row = 1000;

    for($i = 1; $i < $num; $i++){
        $offset = ($i - 1)*$row;
        $orderSql = "select * from (select id,uid,object_id,object_type,price,status,create_time,client_type,pay_time,subject
                  from orders where object_id in(".$cids.") AND status = 1 and object_type = 1 and pay_time is NOT NULL AND price > 0 order by create_time) 
                  t group by t.uid limit ".$offset.','.$row;

        $orderRes = mysqli_query($link, $orderSql);
        $orderArr = mysqli_fetch_all($orderRes,MYSQLI_ASSOC);
        if(empty($orderArr)){
            $num = $i;
            break;

        }else{
            foreach ($orderArr as $or){
                $userOrderSql = "select id,uid,object_id,object_type,price,status,create_time,client_type,pay_time,subject from orders where uid = ".$or['uid'].' and create_time <= "'.$or['create_time'].'" and price > 0 and status = 1';
                $userOrderRes = mysqli_query($link,$userOrderSql);
                $userOrderArr = mysqli_fetch_all($userOrderRes,MYSQLI_ASSOC);

                $insertSql = "insert into orders_user_courses(order_id,uid,object_id,object_type,price,status,create_time,client_type,pay_time,subject,courses_product,courses_point) VALUE ";
                $insertSql .= '('.$or['id'].','.$or['uid'].','.$or['object_id'].','.$or['object_type'].','.$or['price'].','.$or['status'].',"'.$or['create_time'].'","'.$or['client_type'].'","'.$or['pay_time'].'","'.$or['subject'].'","'.$coursesProduct.'","'.$coursesPoint.'"),';

                if(!empty($userOrderArr)){
                    foreach ($userOrderArr as $us){
                        if($or['id'] != $us['id']){
                            //验证是否已添加过
                            $checkOrderIdSql = "select count(id) AS num from orders_user_courses WHERE order_id = ".$us['id'];
                            $checkOrderRes = mysqli_query($linkTest,$checkOrderIdSql);
                            $checkOrderArr = mysqli_fetch_assoc($checkOrderRes);
                            if($checkOrderArr['num'] <= 0){
                                $insertSql .= '('.$us['id'].','.$us['uid'].','.$us['object_id'].','.$us['object_type'].','.$us['price'].','.$us['status'].',"'.$us['create_time'].'","'.$us['client_type'].'","'.$us['pay_time'].'","'.$us['subject'].'","'.$coursesProduct.'","'.$coursesPoint.'"),';
                            }else{
                                echo '重复订单：'.$us['id'].'===';
                                continue;
                            }
                        }
                    }
                }

//                $insertSql = rtrim($insertSql,',');
//                $res = mysqli_query($linkTest,$insertSql);
//                if(!$res){
//                    echo $insertSql.'== 失败！';
//                    break;
//                }

            }
        }

        var_dump(''.$offset.' - '.$row." 完成 ");
    }
}


