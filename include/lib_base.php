<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 2016/6/2
 * Time: 14:08
 */

//递归转义数组
function _addslashes($arr){
    foreach($arr as $k=>$v){
        if(is_string($v)){
            $arr[$k] = addslashes($v);
        }elseif(is_array($v)){
            $arr[$k] = _addslashes($v);
        }
    }
    return $arr;
}