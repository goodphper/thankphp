<?php
/**
 * Created by PhpStorm.
 * User: Home
 * Date: 2016/7/6
 * Time: 23:29
 **/
define('ACC',true);

require ('../include/init.php');

$goods = new GoodsModel();
$goodslist = $goods->getGoods();
// print_r($goodslist);die;


include (ROOT.'view/admin/goods_manage.html');

