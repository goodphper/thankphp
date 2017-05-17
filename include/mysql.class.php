<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 2016/6/2
 * Time: 14:10
 */
//echo 111;die;
//数据库类
defined('ACC')||exit('ACC Denied');
class mysql extends db {
    private static $ins = NULL;
    private $conn = NULL;
    private $conf = array();


    protected function __construct() {
        $this->conf = conf::getIns();

        $this->connect($this->conf->host,$this->conf->user,$this->conf->pwd);
        $this->select_db($this->conf->db);
        $this->setChar($this->conf->char);
    }


    public function __destruct(){

    }

    /*
    连接服务器
    parms $h 服务器地址
    parms $u 用户名
    parms $p 密码
    return bool
    */
    public function connect($h,$u,$p){
        $this->conn = mysql_connect($h,$u,$p);
        if(!$this->conn){
            $err = new Exception('连接失败');
            throw $err;//抛出异常,外面用try/cach接收
        }
    }

    //选择数据库
    protected function select_db($db){
        $sql = 'use '.$db;//use 后面记得留个空格
        $this->query($sql);
    }

    //设置字符集
    protected function setChar($char){
        $sql = 'set names '.$char;//set names 后面记得留个空格
        $this->query($sql);
    }

    /*
    发送查询
    parms $sql 发送的sql语句
    return mixed bool/resource
    */
    public function query($sql){
        $rs = mysql_query($sql,$this->conn);
        log::write($sql);
        return $rs;
    }

    /*
    查询多行数据
    parms $sql select型语句
    return array/bool
    */
    public function getAll($sql){
        $rs = $this->query($sql);
        $list = array();
        while($row = mysql_fetch_assoc($rs)){
            $list[] = $row;
        }
        return $list;
    }



    public static function getIns() {
        if(!(self::$ins instanceof self)) {
            self::$ins = new self();
        }

        return self::$ins;//不要忘了return
    }


    /*
    查询单行数据
    parms $sql select型语句
    return array/bool
    */
    public function getRow($sql){
        $rs = $this->query($sql);
        return mysql_fetch_assoc($rs);
    }

    /*
    查询单个数据
    parms $sql select型语句
    return array/bool
    */
    public function getOne($sql){
        $rs = $this->query($sql);
        $row = mysql_fetch_row($rs);
        return $row[0];
    }

    /*
    自动执行insert/update语句
    parms $sql select型语句
    return array/bool

    $this->autoExecute('user',array('username'=>'zhansan','email'=>'zhang@163.com'),'insert');
    将发生 自动形成 insert into user (username,email) values ('zhangsan','zhang@163.com');

    */

    //这个拼接要再整理下$where='where 1 limit 1'

    public function autoExecute($table,$arr,$mode='insert',$where = ' where 1 limit 1') {
        /*    insert into tbname (username,passwd,email) values ('',)
        /// 把所有的键名用','接起来
        // implode(',',array_keys($arr));
        // implode("','",array_values($arr));
        */

        if(!is_array($arr)) {
            return false;
        }

        if($mode == 'update') {
            $sql = 'update ' . $table .' set ';
            foreach($arr as $k=>$v) {
                $sql .= $k . "='" . $v ."',";
            }
            $sql = rtrim($sql,',');
            $sql .= $where;

            return $this->query($sql);
        }

        $sql = 'insert into ' . $table . ' (' . implode(',',array_keys($arr)) . ')';
        $sql .= ' values (\'';
        $sql .= implode("','",array_values($arr));
        $sql .= '\')';

        return $this->query($sql);

    }



    //返回影响行数的函数
    public function affected_rows(){
        return mysql_affected_rows($this->conn);
    }

    //返回最新的auto_increment列的自增长的值
    public function insert_id(){
        return mysql_insert_id($this->conn);
    }


}