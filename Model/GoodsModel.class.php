<?php
/**
 * Created by PhpStorm.
 * User: Home
 * Date: 2016/6/5
 * Time: 11:27
 **/

defined('ACC')||exit('ACC Denied');

class GoodsModel extends Model{
    protected $table = 'goods';
    protected $pk = 'goods_id';
    protected $fields = array('goods_id','goods_sn','cat_id','goods_name','shop_price','goods_number','goods_brief','goods_desc','thumb_img','goods_img','ori_img','is_sale','is_hot','class_start','class_end','is_delete','add_time','last_update');//表的字段

    protected $_auto = array(
        array('is_hot','value',0),
        array('add_time','function','time')
    );//自动填充，针对哪些字段填充,'add_time','function','time',自动填充一个函数的返回值


    protected $_valid = array(
        array('goods_name',1,'必须有商品名','require'),
        array('class_start',1,'必须有上课时间','require'),
        array('class_end',1,'必须有下课时间','require'),
        array('cat_id',1,'栏目id必须是整型值','number'),
        array('is_hot',0,'is_new只能是0或1','in','0,1'),
        array('goods_brief',2,'商品简介应在10到200字符','length','10,200')
    );//自动过滤


    /**
     * @param $data
     * return bool
     * 已写到父类中
     */
//    public function add($data){
//       return $this->db->autoExecute($this->table,$data);
//    }

    /**
     * @param $id
     *
     * 把商品放到回收站，即把is_delete字段设置为1
     */
    public function trash($id){
        return $this->update(array('is_delete'=>1),$id);
    }

    /**
     * 获取is_delete不为1的商品
     */
    public function getGoods(){
        $sql = 'select * from '.$this->table.' where is_delete = 0';
        return $this->db->getAll($sql);
    }
    
    public function getTrash(){
        $sql = 'select * from '.$this->table.' where is_delete = 1';
        return $this->db->getAll($sql);
    }

    /**
     * 创建商品货号
     */
    public function createSn(){
        $sn = 'SN'.date('Ymd').mt_rand(10000,99999);

        $sql = 'select count(*) from '.$this->table." where goods_sn= '".$sn."'";
        return $this->db->getOne($sql)?$this->createSn():$sn;
    }


        /**
         * 取出指定条数的新品
         * select goods_id,goods_name,shop_price,market_price,thumb_img from goods where is_new=1 order by add_time limit 5
         */
    public function getNew($n=5){
        $sql = 'select goods_id,goods_name,shop_price,market_price,thumb_img from '.$this->table.' where is_new=1 order by add_time limit '.$n;
        return $this->db->getAll($sql);
    }


    //取出指定栏目的商品
//顶级分类下面没有直接的商品，要注意，商品是放在大栏目下的小栏目，找出$cat_id的所有子孙栏目，再查下面的商品
    /**
     * @param $cat_id
     * @param int $offset 偏移量
     * @param int $limit 取出条目
     * @return array
     */
    public function catGoods($cat_id,$offset=0,$limit=5){
        $category = new CatModel();
        $cats = $category->select();//取出所有栏目
        $sons = $category->getCatTree($cats,$cat_id);//取出给定栏目的子孙栏目
        $sub = array($cat_id);
        if(!empty($sons)){//没有子节点，最末端
            foreach($sons as $v){
                $sub[] = $v['cat_id'];
            }
            };
        $in = implode(',',$sub);
        $sql = 'select goods_id,goods_name,shop_price,market_price,thumb_img from '.$this->table.' where cat_id in('.$in.') order by add_time limit '.$offset.','.$limit;
        
        return $this->db->getAll($sql);
    }


    //获取总条数，分页类所需
    public function catGoodscount($cat_id){
        $category = new CatModel();
        $cats = $category->select();//取出所有栏目
        $sons = $category->getCatTree($cats,$cat_id);//取出给定栏目的子孙栏目
        $sub = array($cat_id);
        if(!empty($sons)){//没有子节点，最末端
            foreach($sons as $v){
                $sub[] = $v['cat_id'];
            }
        };
        $in = implode(',',$sub);
        $sql = 'select count(*) from '.$this->table.' where cat_id in('.$in.')';
        return $this->db->getOne($sql);
    }


    /**
     * 获取购物车里的商品详细信息
     * 参数：$items ，商品数组
     * 返回商品数组的详细信息
     */
    public function getCartGoods($items){
        foreach($items as $k=>$v){
            //循环购物车中商品，每循环一次，按商品ID，取出数据，追加到items后面
            $sql = 'select goods_id,goods_name,thumb_img,shop_price,market_price from '.$this->table.' where goods_id ='.$k;
            $row = $this->db->getRow($sql);
            $items[$k]['thumb_img'] = $row['thumb_img'];
            $items[$k]['market_price'] = $row['market_price'];
        }
        return $items;
    }




}


