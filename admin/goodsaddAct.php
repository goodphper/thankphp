<?php


define('ACC',true);
require ('../include/init.php');


$goods = new GoodsModel();

$data = array();
//自动过滤，去除goods表里不含的字段
$data = $goods->_facade($_POST);

//上课时间和下课时间转为时间戳，方便存储和计算
$data['class_start'] = strtotime($_POST['class_start']);
$data['class_end'] = strtotime($_POST['class_end']);

if($data['class_start']>=$data['class_end']){
    $msg = '下课时间不得早于或等于上课时间';
    include('../view/admin/msg.html');
    exit;
   }


//自动填充
$data = $goods->_autoFill($data);

//echo '<pre>';
//print_r($data);

//因为是添加商品，所以直接把添加时间赋值给最后修改时间
$data['last_update'] = $data['add_time'];


//自动添加商品货号
if(empty($data['goods_sn'])){
         $data['goods_sn'] = $goods->createSn();
   }

//echo '<pre>';
//print_r($data);

// 自动验证
if(!$goods->_validate($data)){
    $msg = implode(',',$goods->getErr());
    include('../view/admin/msg.html');
    exit;
}


//上传图片
$uptool = new UpTool();
//echo '<pre>';
//print_r($uptool->up('ori_img'));die;
$ori_img = $uptool->up('ori_img');

if($ori_img){
    $data['ori_img'] = $ori_img;
}


//如果$ori_img上传成功，生成300*400大小的缩略图

if($ori_img){
    //加上绝对路径,拼接原始图的完整路径
    $ori_img = ROOT. $ori_img;

    //拼接中等缩略图的路径
    $goods_img = dirname($ori_img).'/goods_'.basename($ori_img);

    if(ImageTool::thumb($ori_img,$goods_img,300,400)){
        $data['goods_img'] = str_replace(ROOT,'',$goods_img);
    }
   }

//再次生成浏览时的缩略图 160*220
//和上面一样，先拼接好缩略图的路径
$thumb_img = dirname($ori_img).'./thumb_'.basename($ori_img);

if(ImageTool::thumb($ori_img,$thumb_img,80,80)){
         $data['thumb_img'] = str_replace(ROOT,'',$thumb_img);
   }

//然后再把$data整个数组添加到数据库中
if($goods->add($data)){
    $msg = '课程发布成功';
    include('../view/admin/msg.html');
   }




