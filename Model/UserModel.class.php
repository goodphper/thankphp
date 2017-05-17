<?php
/**
 * Created by PhpStorm.
 * User: Home
 * Date: 2016/6/9
 * Time: 23:28
 **/
defined('ACC')||exit('ACC Denied');


class UserModel extends Model{
    protected $table = 'user';
    protected $pk = 'goods_id';
    protected $fields = array('user_id','username','email','passwd','regtime','lastlogin');

    protected $_valid = array(
//        array('username',1,'用户名必须在4-16个字符内','length','4,16'),//4，16要传到check（）
        array('username',1,'用户名必须存在','require'),
        array('username',0,'用户名必须在4-16个字符内','length','4,16'),
        array('email',1,'email非法','email'),
        array('passwd',1,'passwd不能为空','require')
    );


//自动填充
    protected $_auto = array(
        array('regtime','function','time')
    );//针对哪些字段填充,'add_time','function','time',自动填充一个函数的返回值


    //用户注册
    public function reg($data){
        if($data['passwd']){
            $data['passwd'] = $this->encPasswd($data['passwd']);
            };
        return $this->add($data);
    }

    protected function encPasswd($p){
        return md5($p);
    }

    //根据用户名，查询用户信息,如果传了passwd，则把数据全部取出来，用作登录时对比
    public function checkUser($username,$passwd=''){
        if($passwd == ''){
                $sql = 'select count(*) from '.$this->table." where username = '".$username."'";
                return $this->db->getOne($sql);
            }else{
                $sql = 'select user_id,username,email,passwd from '.$this->table." where username='".$username."'";
                $row = $this->db->getRow($sql);
                if(empty($row)){
                    return false;
                }
            if($row['passwd']!== $this->encPasswd($passwd)){
                return false;
            }
            unset($row['passwd']);
            return $row;
        }
    }
}






?>