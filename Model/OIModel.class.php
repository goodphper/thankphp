<?php
/**
 * Created by PhpStorm.
 * User: Home
 * Date: 2016/6/9
 * Time: 23:28
 **/
defined('ACC')||exit('ACC Denied');


class OIModel extends Model{
    protected $table = 'orderinfo';
    protected $pk = 'order_id';
    protected $fields = array('order_id','order_sn','user_id','username','zone','address','zipcode','reciver','email','tel','mobile','building','best_time','add_time','order_amount','pay');

    protected $_valid = array(
//        array('username',1,'用户名必须在4-16个字符内','length','4,16'),//4，16要传到check（）
        array('reciver',1,'收货人不能为空','require'),
        array('pay',1,'必须选择支付方式','in','4,5'),
        array('email',1,'email非法','email'),
    );


//自动填充
    protected $_auto = array(
        array('add_time','function','time')
    );//针对哪些字段填充,'add_time','function','time',自动填充一个函数的返回值


    //根据年月日生成订单号，同自动生成商品货号
    public function orderSn(){
        $sn = 'OI'.date('Ymd').mt_rand(10000,99999);
        //判断订单号是否已存在
        $sql = 'select count(*) from '.$this->table.' where order_sn='."'$sn'";
        return $this->db->getOne($sql)?orderSn($sn):$sn;
    }

    //撤销订单
    public function invoke($order_id){
        $this->delete($order_id);//删订单
        $sql = 'delete from ordergoods where order_id='.$order_id;//再上订单对应的商品
        return $this->db->query($sql);
    }

}






?>