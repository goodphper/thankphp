<?php
/**
 * Created by PhpStorm.
 * User: Home
 * Date: 2016/6/4
 * Time: 18:20
 **/

//操作category表
defined('ACC')||exit('ACC Denied');
class CatModel extends Model{
    protected $table = 'category';

    /**
     * 传一个关联数组，键对应表中的列，值对应表中的值
     * add()自动插入数据
     */
    public function add($data){
        return $this->db->autoExecute($this->table,$data);
    }

    //获取本表下面所有的数据
    public function select(){
        $sql = 'select cat_id,cat_name,intro,parent_id from '.$this->table;
        return $this->db->getAll($sql);
    }

    //根据主键取出一行数据
    public function find($cat_id){
        $sql = 'select * from '.$this->table.' where cat_id= '.$cat_id;
        return $this->db->getRow($sql);
    }

    /**
     * getCatTree,获得栏目树
     * parm: id
     * return: $id栏目的子孙树
     */
    public function getCatTree($arr,$id=0,$lev=0){
        $tree = array();
        foreach ($arr as $v) {
            if ($v['parent_id'] == $id) {
                $v['lev'] = $lev;
                $tree[] = $v;
                $tree = array_merge($tree,$this->getCatTree($arr,$v['cat_id'],$lev+1));
            }
        }
        return $tree;
    }

    //查子栏目,给一个ID，查这个ID下面的子栏目
    public function getSon($id){
        $sql = 'select cat_id,cat_name,intro,parent_id from '.$this->table.' where parent_id='.$id;
        return $this->db->getAll($sql);
    }

    //迭代查家谱树，给一个id，查这个ID的家谱树
    public function getTree($id=0){
        $tree = array();
        $cats = $this->select();
        while($id>0){
        foreach($cats as $v){
            if($v['cat_id']==$id){
                $tree[] = $v;
                $id = $v['parent_id'];
                break;
            }
        }
        }
        return array_reverse($tree);
    }

    //删除栏目
    public function delete($cat_id=0){
        $sql = 'delete from  '.$this->table.' where cat_id= '.$cat_id;
        $this->db->query($sql);
        return $this->db->affected_rows();
    }

    //更新修改
    public function update($data,$cat_id=0){
        $this->db->autoExecute($this->table,$data,'update',' where cat_id='.$cat_id);
        return $this->db->affected_rows();
    }





}


?>