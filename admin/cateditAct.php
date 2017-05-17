<?php

define('ACC',true);
require ('../include/init.php');



$data = array();
if(empty($_POST['cat_name'])){
    exit('栏目名不能为空');
}

$data['cat_name'] = $_POST['cat_name'];
$data['parent_id'] = $_POST['parent_id'] + 0;
$data['intro'] = $_POST['intro'];

$cat_id = $_POST['cat_id'];


$cat = new CatModel();
$trees = $cat->getTree($data['parent_id']);
//echo '<pre>';
//print_r($trees);

$flag = true;
foreach($trees as $v){
    if($v['cat_id']==$cat_id){
             $flag = false;
        break;
       };
}

if(!$flag){
    $msg = '父栏目选取错误,不能选择自己的子栏目';
        include ('../view/admin/msg.html');
        exit;
   };

if($cat->update($data,$cat_id)){
    $msg = '修改成功';
    include('../view/admin/msg.html');
}else{
    $msg = '修改失败';
    include('../view/admin/msg.html');
}







?>