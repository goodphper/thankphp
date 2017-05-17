<?php
/**
 * Created by PhpStorm.
 * User: Home
 * Date: 2016/5/31
 * Time: 2:50
 *配置文件读取类
 *
 **/

class conf{
    protected static $ins = null;
//    protected static $data = array();
    protected $data = array();

    final protected function __construct(){
        //一次性把配置文件信息读取，赋给成员属性$data
        include (ROOT.'include/config.inc.php');
        $this->data = $_CFG;
    }

    final protected function __clone(){

    }

    //单例
    public static function getIns(){
        if(self::$ins instanceof self){
            return self::$ins;
        }else{
            self::$ins = new self();
            return self::$ins;
        }
    }

    //用魔术方法，读取data内的内容
    public function __get($key){
        if(array_key_exists($key,$this->data)){
            return $this->data[$key];
        }else{
            return null;
        }
    }

    //动态增加或改变配置选项
    public function __set($key, $value){
        $this->data[$key] = $value;
    }


}

//$conf = conf::getIns();
//print_r($conf);

//读取选项
//echo $conf->host;

//追加选项
//$conf->template_dir = 'D:/smarty';
//var_dump($conf->template_dir);


?>