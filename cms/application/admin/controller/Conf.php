<?php
namespace app\admin\controller;

use think\Loader;

class Conf extends Base
{
    //配置列表
    public function confLst(){
        $confRes = db('conf')->select();

        foreach ($confRes as $k=>$v){
            if ($v['dt_type'] == 2 || $v['dt_type'] == 4){
                    $vl = explode(',', $v['values']);
                    $confRes[$k]['values'] = $vl;
            }elseif ($v['dt_type'] == 3){
                $vl = explode(',', $v['values']);
                $confRes[$k]['values'] = $vl;
                $v1 = explode(',', $v['value']);
                $confRes[$k]['value'] = $v1;
            }
        }
        if (request()->isPost()){
            $oldData = db('conf')->column('ename,dt_type');     //查询原有cname,dt_type字段,cname作为键，dt_type作为值
            $newData = input('post.');
           /* $file = request()->file();
            dump($oldData);die;
            // 移动到框架应用根目录/public/admin_uploads/ 目录下
            $info = $file->validate(['size'=>2048,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){

            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }*/
            foreach ($newData as $k=>$v){
                $postArr[] = $k;        //取出发送过来的键
                if (is_array($v)){
                    $newData[$k] = implode(',',$v);
                }
            }
            foreach ($oldData as $k=>$vo){
                if (!in_array($k,$postArr)){   //如果原有的字段(除开dt_type=6时)不在发送过来中，则置为空
                    if($vo != 6)$newData[$k] = '';
                }
                if ($vo == 6 && $_FILES[$k]['tmp_name']){                  //如果dt_type为6时。上传为附件处理

                    $file = request()->file($k);
                    // 移动到框架应用根目录/public/admin_uploads/ 目录下
                    $info = $file->validate(['size'=>1024*1024*2,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public/admin_uploads');
                    if ($info){
                        $saveName = $info->getSaveName();
                            $newData[$k] = $saveName;
                    }else{
                        $this->error($info->getError());
                    }
                }
            }
            //dump($newData);die;
            foreach ($newData as $k=>$v){
                $res = db('conf')->where('ename',$k)->update(['value'=>$v]);
            }
            if ($res !== false){
                $this->success('修改成功！','confLst');
            }else{
                $this->error('修改失败！');
            }
        }
        $this->assign([
            'confRes'   =>  $confRes,
        ]);
       return view();
    }
    //配置管理
    public function lst(){
        $confRes = db('conf')->paginate(6);
        $this->assign([
            'confRes'   =>  $confRes,
        ]);
        return view();
    }
    //配置添加
    public function add(){
        if (request()->isPost()){
            $data = input('post.');
            $data['values'] = str_replace('，',',',$data['values']);
            $validate = Loader::validate('Conf');
            if (!$validate->scene('add')->check($data)){
                $this->error($validate->getError());
            }
            $res = db('conf')->insert($data);
            if ($res){
                $this->success('添加成功！','lst');
            }else{
                $this->error('添加失败！');
            }

            dump(input('post.'));die;
        }
        return view();
    }
    //配置删除
    public function del(){
        $id = input('id');
        $delRes = db('conf')->where(['id'=>$id])->delete();
        if ($delRes){
            $this->success('删除成功！','lst');
        }else{
            $this->error('删除失败！');
        }

    }
    //配置修改
    public function edit(){
        $id = input('id');
        $editRes = db('conf')->where(['id'=>$id])->find();
        if (request()->isPost()){
            $data = input('post.');
            $data['id'] = $id;
            $data['values'] = str_replace('，',',',$data['values']);
            $validate = Loader::validate('Conf');
            if (!$validate->scene('edit')->check($data)){
                $this->error($validate->getError());
            }
            $editRes = db('conf')->update($data);
            if ($editRes !== false){
                $this->success('修改成功！','lst');
            }else{
                $this->error('修改失败！');
            }
        }
        $this->assign([
            'editRes'  =>  $editRes,
        ]);
        return view();

    }

}
