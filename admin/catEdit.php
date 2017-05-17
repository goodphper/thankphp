<?php

define('ACC',true);
require ('../include/init.php');


//接数据

$cat_id = $_GET['cat_id'] + 0;
$cat = new CatModel();

$catinfo = $cat->find($cat_id);

$catlist = $cat->select();
$catlist = $cat->getCatTree($catlist);

include('../view/admin/cate_edit.html');


?>