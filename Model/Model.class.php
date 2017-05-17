<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 2016/6/2
 * Time: 17:54
 */
//防止直接跳墙
defined('ACC')||exit('ACC Denied');

class Model{
    protected $table = NULL;//model所控制的表
    protected $db = NULL;//引入的mysql对象
    protected $pk = NULL;//主键
    protected $fields = array();//自动过滤，表的字段
    protected $_auto = array();//自动完成，针对哪些字段填充
    protected $_valid = array();//自动验证，验证规则
    protected $error = array();//自动验证，错误提示

    public function __construct(){
        $this->db = mysql::getIns();//static的好处就在这，可以不用实例化，直接调用
    }

    public function table($table){
        $this->table = $table;
    }


    /**
     * 自动过滤
     * 负责把传来的数组，清除掉不用的单元，留下和表字段对应的单元
     * 循环数组，分别判断其KEY，是否是表的字段
     * 要先有表的字段，可以用desc分析
     * 也可手动写好，先手动写
     */

    public function _facade($array=array()){
        $data = array();
        foreach($array as $k=>$v){
            if(in_array($k,$this->fields)){//判断$k是否在表的字段里
                $data[$k]=$v;
            }
        }
        return $data;
    }

    /**
     * 自动填充
     * 负责把表中需要值的字段，而POST又没传的字段，给赋值，
     * 比如post里没有add_time，则自动赋值time()
     */
    public function _autoFill($data){
        foreach($this->_auto as $k=>$v){
//            echo $v[0].',';//is_hot,add_time,
//            echo $v[2].',';//0,time,
            if(!array_key_exists($v[0],$data)){
                switch($v[1]){
                    case 'value':
                        $data[$v[0]] = $v[2];
                        break;
                    case 'function':
                        $data[$v[0]] = call_user_func($v[2]);
                        break;
                }
            }
        }
        return $data;
    }


    //自动验证
    /**
     * @param $data
     * 格式 $this->_valid = array(
                array('验证的字段名',0/1/2(验证场景),'报错提示','require/in/between/length(哪些情况，范围，某个范围 )'),
     * );
            protected $_valid = array(
                array('goods_name',1,'必须有商品名','require'),
                array('cat_id',1,'栏目id必须是整型值','number'),
                array('is_new',0,'is_new只能是0或1','in','0,1'),
                array('goods_brief',2,'商品简介应在10到200字符','length','10,200'),
            );
     */
    public function _validate($data){
        if(empty($this->_valid)){
            return true;
        }
        $this->error = array();
        foreach($this->_valid as $k=>$v){
            switch($v[1]){
                case 1:
                    if(!isset($data[$v[0]])){
                        $this->error[] = $v[2];
                        return false;
                    }

                    if(!isset($v[4])) {
                        $v[4] = '';
                    }

                    if(!$this->check($data[$v[0]],$v[3],$v[4])) {
//                        echo $data[$v[0]];
//                        echo 111;
                        $this->error[] = $v[2];
                        return false;
                    }
                    break;
                case 0:
                    if(isset($data[$v[0]])){
                        if(!$this->check($data[$v[0]],$v[3],$v[4])){
                            $this->error[] = $v[2];
                            return false;
                        }
                    }
                    break;
                case 2:
                    if(isset($data[$v[0]]) && !empty($data[$v[0]])){
                        if(!$this->check($data[$v[0]],$v[3],$v[4])){
                            $this->error[] = $v[2];
                            return false;
                        }
                    }
            }
        }
        return true;
    }



    public function getErr(){
        return $this->error;
    }

    //('goods_brief',2,'商品简介应在10到200字符','length','10,200') ,$v[0]],$v[3],$v[4])
    protected function check($value,$rule='',$parm=''){
        switch($rule){
            case 'require':
//                echo 222;
                return !empty($value);
            case 'in':
                $tmp = explode(',',$parm);
                return in_array($value,$tmp);
            case 'number':
                return is_numeric($value);
            case 'between':
                list($min,$max) = explode(',',$parm);
                return $value >= $min && $value <= $max;
            case 'length':
                list($min,$max) = explode(',',$parm);
                return strlen($value) >= $min && strlen($value) <= $max;
            case 'email':
                //判断$value是否是email，可用正则表达式或系统函数判断，此处用系统函数
                return filter_var($value,FILTER_VALIDATE_EMAIL) !== false;//也可直接return filter_var($value,FILTER_VALIDATE_EMAIL);更精确
            default :
                return false;
        }
    }




    /**
     * 在model父类里写最基本的增删改查操作
     */
    /**
     * 增
     * @param $data
     * @return bool|resource
     */
    public function add($data){
        return $this->db->autoExecute($this->table,$data);
    }

    /**
     * 删除操作
     * @param $id 主键
     * return 影响的行数
     */

    public function delete($id){
        $sql = 'delete from '.$this->table.' where '.$this->pk.'='.$id;
        if($this->db->query($sql)){
            return $this->db->affected_rows();
        }else{
            return false;
        }
    }

    /**
     * @param $data
     * @param $id
     * @return bool|int返回修改的条数
     */
    public function update($data,$id){
        //$table,$arr,$mode='insert',$where = ' where 1 limit 1'
        $rs = $this->db->autoExecute($this->table,$data,'update',' where '.$this->pk.'='.$id);
        if($rs){
            return $this->db->affected_rows();
        }else{
            return false;
        }
    }

    /**
     * @return array
     * 查出所有
     */
    public function select(){
        $sql = 'select * from '.$this->table;
        return $this->db->getAll($sql);
    }

    /**
     * @param $id
     * @return array
     * 查出一条数据
     */
    public function find($id){
        $sql = 'select * from '.$this->table.' where '.$this->pk.'='.$id;
        return $this->db->getRow($sql);
    }

    public function insert_id(){
        return $this->db->insert_id();
    }



}