<?php
/**
 * Created by PhpStorm.
 * User: Home
 * Date: 2016/6/7
 * Time: 0:20
 **/

/**
 * 单文件上传类
 *
 */

defined('ACC')||('ACC Denied');

/**
 * 功能
 * 上传文件
 * 配置允许的后缀
 * 配置允许的文件大小
 * 随机生成目录
 * 随机生成文件名
 *
 * 获取文件的后缀
 * 判断文件的后缀
 *
 * 判断大小
 *
 * 良好的报错信息
 */



class UpTool{
    protected $allowExt = 'jpg,jpeg,gif,bmp,png';
    protected $maxSize = 1;//1M,兆为单位

    protected $errno = 0;//错误代码
    protected $error = array(
        0=>'无错',
        1=>'上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值',
        2=>'上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值',
        3=>'文件只有部分被上传',
        4=>'没有文件被上传',
        6=>'找不到临时文件夹',
        7=>'文件写入失败',
        8=>'不允许的文件后缀',
        9=>'文件大小超出类的允许',
        10=>'创建目录失败',
        11=>'移动文件失败'
    );



/*
 *     //获取文件信息，放到$file里，也可不写
 *  protected $file = NULL;//储存上传文件的信息
    protected function getFile($key){
        $this->file = $_FILES[$key];//把上传域的name，如pic赋给file
    }
*/
    /**
     * @param $key
     * 上传
     */
    public function up($key){
        if(!isset($_FILES[$key])){
            return false;
        }
        $f = $_FILES[$key];
        //检验上传有没有错误
        if($f['error']){
            $this->errno = $f['error'];
            return false;
        }


        //获取后缀
        $ext = $this->getExt($f['name']);
        //检查后缀
        if(!$this->isAllowExt($ext)){
            $this->errno = 8;
            return false;
        }
        //检查大小
        if(!$this->isAllowSize($f['size'])){
            $this->errno = 9;
            return false;
        }
        //通过，
        //创建目录
        $dir = $this->mk_dir();
        if($dir == false){
            $this->errno = 10;
            return false;
        }

        //生成随机文件名
        $newname = $this->randName().'.'.$ext;
        $dir = $dir.'/'.$newname;

        //移动
        if(!move_uploaded_file($f['tmp_name'],$dir)){
            $this->errno = 11;
            return false;
        }

        return str_replace(ROOT,'',$dir);

    }

    public function getErr(){
        return $this->error[$this->errno];
    }


    /**
     *修改后缀接口
     */
    public function setExt($exts){
        $this->allowExt = $exts;
    }

    /**
     * 修改大小接口
     */
    public function setSize($num){
        $this->maxSize = $num;
    }



    /**
     * 获取后缀
     * @param $file
     * return $ext
     */
    protected function getExt($file){
        $tmp = explode('.',$file);
        return end($tmp);
    }

    /**
     * 判断后缀是否合法
     * @param $ext
     * return bool
     * 通过getExt()获取文件后缀，和$allowExt对比
     * 防止后缀大小写的问题,全部转成小写
     */
    protected function isAllowExt($ext){
        return in_array(strtolower($ext),explode(',',strtolower($this->allowExt)));
    }


    /**
     * @param $size
     * @return bool
     * 检查文件大小
     */
    protected function isAllowSize($size){
        return $size <= $this->maxSize * 1024 * 1024;
    }

    /**
     * 按日期创建目录
     */
    protected function mk_dir(){
//        $dir = ROOT . 'data/images/'. date('Ym/d').'/';
        $dir = ROOT . 'data/images/'. date('Ym/d');
        if(is_dir($dir) || mkdir($dir,0777,true)){
        return $dir;
        }else{
            return false;
        }
    }

    /**
     * 生成随机文件名
     */
    protected function randName($length=6){
        $str = 'abcdefghijkmnpqrstuvwxyz23456789';
        return substr(str_shuffle($str),0,$length);

    }


}






?>