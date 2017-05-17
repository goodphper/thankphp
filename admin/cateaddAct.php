<?php
/**
 * Created by PhpStorm.
 * User: Home
 * Date: 2016/7/7
 * Time: 1:16
 **/
define('ACC',true);
require ('../include/init.php');

//echo '<pre>';
//print_r($_POST);die;

//接数据
$data = array();
$data = $_POST;


//检验数据

if(empty($data['cat_name'])){
    $msg = '对不起，类名不能为空，请返回重新填写！';
    include (ROOT.'view/admin/msg.html');
    exit;
   };



//插入数据
$categray = new CatModel();
if($categray->add($data)){
    $msg = '栏目添加成功';
    include (ROOT.'view/admin/msg.html');
   }else{
    $msg = '栏目添加失败';
    include (ROOT.'view/admin/msg.html');
};







?>