<?php
/**
 * Created by PhpStorm.
 * User: Home
 * Date: 2016/5/31
 * Time: 0:36
 *
 * 数据库抽象类
 *
 **/
defined('ACC')||exit('ACC Denied');
abstract class db{
    /**
     *
     * 连接服务器
     * $h $u $p 服务器 用户名 密码
     * return bool
     **/
    public abstract function connect ($h,$u,$p);


    //发送查询 return bool/souse
    public abstract function query($sql);
    
    /**
     * 
     * 查询多行数据
     *return  array/bool
     **/
    public abstract function getAll($sql);


    /**
     *
     * 查询单行数据
     *return  array/bool
     **/
    public abstract function getRow($sql);

    /**
     *
     * 查询单个数据
     *return  array/bool
     **/
    public abstract function getOne($sql);


    /**
     *
     * 自动执行insert/update语句
     * 自动拼接sql语句
     *return  array/bool
     **/
    public abstract function autoExecute($table,$data,$act='insert',$where='');


}

?>