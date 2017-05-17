<?php
/**
 * Created by PhpStorm.
 * User: Home
 * Date: 2016/7/7
 * Time: 1:16
 **/
define('ACC',true);
require ('../include/init.php');


$cat_id = $_GET['cat_id'];

$cat = new CatModel();
$sons = $cat->getSon($cat_id);

if(!empty($sons)){
         $msg = '有子栏目不允许删除';
        include ('../view/admin/msg.html');
   };

if($cat->delete($cat_id)){
    $msg = '删除成功';
    include ('../view/admin/msg.html');
   }else{
    $msg = '删除失败';
    include ('../view/admin/msg.html');
};


?>