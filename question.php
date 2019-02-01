<?php
/**
 * Created by PhpStorm.
 * User: xieyan
 * Date: 2019/1/23
 * Time: 下午4:30
 */

include_once './MMysql.php';

class Question{

    static $obj = NULL;
    public $mysql ;

    private function __construct()
    {
        $this->mysql = new MMysql();
    }

    static function getObject()
    {
        if(!isset(self::$obj)){
            self::$obj = new self();
        }
        return self::$obj;
    }

    public function getList()
    {
        $sql = 'SELECT * FROM user WHERE user_id < 5 AND user_id > 1';
        return $this->mysql->doSql($sql);
    }
}
$obj = Question::getObject();
var_dump($obj->getList());