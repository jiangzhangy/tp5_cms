<?php
namespace app\admin\model;

use think\Model;

class Cate extends Model
{
    //获取栏目树结构
    public function cateTree($pro = ''){
        if ($pro == ''){
            $cateRes = $this->order('sort desc')->select()->toArray();
        }else{
            $map['id'] = ['not in',$pro];
            $cateRes = $this->order('sort desc')->where($map)->select()->toArray();
        }
        return $this->cateSort($cateRes);
    }
    protected function cateSort($data,$id=0,$level=0){
        static $arr = [];
        foreach ($data  as $k=>$v){
            if ($v['pid'] == $id){
                $v['level'] = $level;
                $arr[] = $v;
                $this->cateSort($data,$v['id'],$level+1);
            }
        }
        return $arr;
    }
    //获取当前ID栏目下的子栏目
    public function getChildrenIds($cate_id){
        $data = $this->field('id,pid')->select();
        return $this->_getChildrenIds($data,$cate_id);
    }
    private function _getChildrenIds($data,$cate_id){
        static $arr = [];
        foreach ($data as $k=>$v){
            if ($v['pid'] == $cate_id){
                $arr[] = $v['id'];
                $this->_getChildrenIds($data,$v['id']);
            }
        }
        return $arr;
    }
    //批量删除
    public function batchRemove(array $data){


        foreach ($data as $k => $v){
            $ids[] = $this->getChildrenIds($v);
            $ids[] = intval($v);
        }
        $allId = [];
        if (!empty($ids))
            foreach ($ids as $k=>$v){
                if (is_array($v)){
                    foreach ($v as $k1=>$v1){
                        $allId[] = $v1;
                    }
                }else{
                    $allId [] = $v;
                }
            }
        $allId = array_unique($allId);
        $this->destroy($allId);
    }

}
