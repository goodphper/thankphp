<?php
/**
 * Created by PhpStorm.
 * User: Home
 * Date: 2016/7/6
 * Time: 23:29
 **/
define('ACC',true);

require ('../include/init.php');

$cat = new CatModel();
$catlist = $cat->select();

$catlist = $cat->getCatTree($catlist);

//echo '<pre>';
//print_r($catlist);die;

include (ROOT.'view/admin/cate_manage.html');
