<?php
/**
 * Created by PhpStorm.
 * User: Home
 * Date: 2016/6/12
 * Time: 12:35
 **/


/**
 * 购物车类
 */
defined('ACC')||('ACC Denied');

class CartTool{
    private static $ins = NULL;
    private $items = array();
//    public $sign = 0;//测试是否单例

    final protected function __construct(){
//        $this->sign = mt_rand(1,1000);//测试是否单例


    }

    final protected function __clone(){

    }


    //获取实例，单例模式
    protected static function genIns(){
        if(self::$ins instanceof self){
            return self::$ins;
        }else{
            self::$ins = new self();
            return self::$ins;
        }
    }


    //把购物车的单例对象放到session里
    public static function getCart(){
        //如果session里没有cart实例，就创建一个实例放到session里
        if(!isset($_SESSION['cart']) || !($_SESSION['cart'] instanceof self)){
            $_SESSION['cart'] = self::genIns();
        }
        return $_SESSION['cart'];
    }

    //添加商品
    /**
     * @param $id 商品主键，可以用来做items数组的索引
     * @param $name 商品名称
     * @param $price 商品价格
     * @param int $num 商品数量
     */
    public function addItem($id,$name,$price,$num=1){
        if($this->hasItem($id)){//如果该商品已经存在，则加数量
            $this->incNum($id,$num);
            return;
            };
        $item = array();
        $item['name'] = $name;
        $item['price'] = $price;
        $item['num'] = $num;
        $this->items[$id] = $item;
    }


    //修改购物车中的商品数量
    /**
     * @param $id 商品主键
     * @param int $num
     */
    public function modNum($id,$num=1){
        if(!$this->hasItem($id)){
            return false;
        }
        $this->items[$id]['num'] = $num;
    }


    /**
     * 商品数量+1
     */
    public function incNum($id,$num=1){
        if($this->hasItem($id)){
                 $this->items[$id]['num'] += $num;
            };
    }


    /**
     * 商品数量-1
     */
    public function decNum($id,$num=1){
        if($this->hasItem($id)){
            $this->items[$id]['num'] -= $num;
        };
        //如果减少后，数量为0了，则把这商品从购物车删除；
        if($this->items[$id]['num']-1 < 1){
            $this->delItem($id);
        }
    }


    //判断某商品是否已经存在购物车
    public function hasItem($id){
        return array_key_exists($id,$this->items);
    }

    //删除某个商品
    public function delItem($id){
        unset($this->items[$id]);
    }


    //查询购物车中商品的种类
    public function genCnt(){
        return count($this->items);
    }


    //查询购物车中商品的个数
    public function genNum(){
        if($this->genCnt()==0){
            return 0;
            };
        $sum = 0;
        foreach($this->items as $item){
            $sum += $item['num'];
        }
        return $sum;
    }


    //查询购物车中商品的总金额
    public function getPrice(){
        if($this->genCnt()==0){
            return 0;
        };
        $price = 0.0;
        foreach($this->items as $item){
            $price += $item['num'] * $item['price'];
        }
        return $price;
    }


    //返回购物车中的所有商品
    public function all(){
        return $this->items;
    }


    //清空购物车
    public function clear(){
        $this->items = array();
    }



}


/*
 * session_start();
$cart = CartTool::getCart();

if($_GET['test'] == 'addshaobin'){
$cart->addItem(2,'烧饼',23.4,1);
    echo '成功添加商品到购物车';
}elseif($_GET['test'] == 'addyoutiao'){
    $cart->addItem(3,'油条',66.6,1);
    echo '成功添加商品到购物车';
}elseif($_GET['test'] == 'clear'){
    $cart->clear();
    echo '清空购物车成功';
}elseif($_GET['test']='show'){
    print_r($cart->all());
    echo '<br />';
    echo '共',$cart->genCnt(),'种',$cart->genNum(),'个商品<br />';
    echo '共',$cart->getPrice(),'元';
}else{
    echo '<pre>';
    print_r($cart);
}
*/


//print_r(CartTool::getCart());



?>