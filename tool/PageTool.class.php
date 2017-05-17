<?php
/**
 * Created by PhpStorm.
 * User: Home
 * Date: 2016/6/14
 * Time: 2:34
 **/



defined('ACC')||('ACC Denied');

/**
 * 分页类
 * 分页导航的生成
 * 总条数 $total
 * 每页条数 $perpage
 * 当前页 $page
 */


class PageTool{
    protected $total = 0;
    protected $perpage = 10;
    protected $page = 5;

    public function __construct($total,$page=false,$perpage=false){
        $this->total = $total;
        if($perpage){
                $this->perpage = $perpage;
           };
        if($page){
                $this->page = $page;
           };
    }

    //创建分页导航
    public function show(){
        $cnt = ceil($this->total/$this->perpage);//得到总页数

//例如：http://localhost/frame/tool/PageTool.class.php?id=3&cat=5&page=3
        $uri = ($_SERVER['REQUEST_URI']);//获取如：/frame/tool/PageTool.class.php?id=3&cat=5&page=3
        $parse = parse_url($uri);//获取路径path和请求的参数query如：Array ( [path] => /frame/tool/PageTool.class.php [query] => id=3&cat=5&page=3 )

        //id=3&cat=5&page=3 ,拆成数组，用&分割或者parse_str
        //print_r($parse);
        $param = array();
        if(isset($parse['query'])){
                 parse_str($parse['query'],$param);
           };

        //不管$param数组里，有没有page单元，都unset一下，确保每页page单元
        //即保存除page外的所有单元
        unset($param['page']);


        
          $url = $parse['path'].'?';
        if(!empty($param)){
            $param = http_build_query($param);
            $url = $url.$param.'&';
           };


        //计算页码导航
//        $nav = array();
//        for($i=1;$i<=5;$i++){
//            echo $url.'page='.$i,'<br />';
//        }
        /**
        /frame/tool/PageTool.class.php?id=3&cat=5&page=1
        /frame/tool/PageTool.class.php?id=3&cat=5&page=2
        /frame/tool/PageTool.class.php?id=3&cat=5&page=3
        /frame/tool/PageTool.class.php?id=3&cat=5&page=4
        /frame/tool/PageTool.class.php?id=3&cat=5&page=5
         */

        $nav = array();
        //当前页，样式，右键源码
        $nav[0] = '<span class="page_now">'.$this->page.'</span>';

//        print_r($nav);die;

        //上一页，下一页
        for($left=$this->page-1,$right=$this->page+1;($left>=1||$right<=$cnt)&&count($nav)<=5;){
            if($left>=1){
                array_unshift($nav,'<a href="'.$url.'page='.$left.'">['.$left.']</a>');
                $left-=1;
               };
            if($right<=$cnt){
                array_push($nav,'<a href="'.$url.'page='.$right.'">['.$right.']</a>');
                $right+=1;
               };
        }
    return implode('',$nav) ;
    }
}

//测试分页类
//new PageTool(总条数，当前页，每页条数);

//$page = $_GET['page']?$_GET['page']:1;
//$p = new PageTool(20,$page,6);
//echo $p->show();










?>