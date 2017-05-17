<?php
/**
 * Created by PhpStorm.
 * User: Home
 * Date: 2016/5/31
 * Time: 0:19
 *
 *header("content-type:text/html;charset=utf-8");
 *
 **/
//框架初始化

//设置字符集
header("content-type:text/html;charset=utf-8");

//防止直接跳墙
defined('ACC')||exit('ACC Denied');


//初始化当前绝对路径
//换成正斜线，因为WIN/linux都支持正斜线，linux不支持反斜线
define('ROOT',dirname(dirname(str_replace('\\','/',__FILE__))).'/');
//echo ROOT;
//引入各种类

require(ROOT.'include/lib_base.php');
/*
require(ROOT.'include/conf.class.php');
require(ROOT.'include/config.inc.php');
require(ROOT.'include/db.class.php');
require(ROOT.'include/mysql.class.php');
*/

//自动加载
//substr($class,-5)，$class是类名，所以可以用-5来截取

function __autoload($class) {
    if(strtolower(substr($class,-5)) == 'model') {
        require(ROOT . 'Model/' . $class . '.class.php');
    }else if(strtolower(substr($class,-4)) == 'tool'){
        require (ROOT.'tool/'.$class.'.class.php');
    } else {
        require(ROOT . 'include/' . $class . '.class.php');
    }
}


//调用lib_base.php里的_addslashes()函数，递归过滤
$_GET = _addslashes($_GET);
$_POST = _addslashes($_POST);
$_COOKIE = _addslashes($_COOKIE);

//开启session
session_start();

//设置报错级别
define('DEBUG',true);

if(defined('DEBUG')){
         error_reporting(E_ALL);
   }else{
     error_reporting(0);
   };

//

?>