<?php
namespace app\admin\controller;


class Cate extends Base
{
    //栏目列表
    public function lst(){
        $cateRes = model('Cate')->cateTree();
        $this->assign([
            'cateRes'   =>  $cateRes,
        ]);
        return view();
    }

    /**
     * ajax异步隐藏
     * @return array
     */
    public function ajaxLst(){
        if (request()->isAjax()){
            $cateId = input('cateId');
            $sonRes = model('Cate')->getChildrenIds($cateId);
            return $sonRes;
        }
    }
    /*
     * 栏目添加
     */
    public function add()
    {
        if(request()->isPost()){
            $data = input('post.');
            $validate = validate('Cate');
            //数据验证
            $result = $validate->scene('add')->check($data);
            if(!$result){
                $this->error($validate->getError());
            }
            $res = db('cate')->insert($data);
            if ($res){
                $this->success('添加栏目成功！ ','lst');
            }else{
                $this->error('添加栏目失败！ ');
            }
        }
        //获取栏目树结构
        $cateRes = model('Cate')->cateTree();
        //接受栏目ID
        $cateId = input('cate_id');
        //模型调用
        $modelRes = db('Model')->field('id,model_name')->select();
        $this->assign([
            'cateRes'     =>  $cateRes,
            'cateId'      =>  $cateId,
            'modelRes'    =>  $modelRes,
        ]);
        return view();
    }
    //栏目修改
    public function edit(){
        if (request()->isPost()){
            $data = input('post.');
            //如果数据中存在status 则跟新为1 状态
            if (!isset($data['status'])){
                $data['status'] = 1;
            }
            //数据验证
            $validate = validate('Cate');
            $result = $validate->scene('edit')->check($data);
            if(!$result){
                $this->error($validate->getError());
            }
            $res = db('Cate')->update($data);
            if($res !== false){
                $this->success('修改成功！','lst');

            }else{
                $this->error('修改失败！');
            }

            }
        $cateID = input('cate_id');
        $cateDetail = db('cate')->findOrFail($cateID);
        $cateSon = model('Cate')->getChildrenIds($cateID);
        $cateSon[] = $cateID;
        $cateRes = model('Cate')->cateTree($cateSon);
        //模型调用
        $modelRes = db('Model')->field('id,model_name')->select();
        $this->assign([
            'cateRes'       =>  $cateRes,
            'cateDetail'    =>  $cateDetail,
            'modelRes'      =>  $modelRes,
        ]);
        return view();
    }
    //栏目添加图片处理
    public function upImg(){
        $file = request()->file('img');
        if($file) {
            $info = $file->move(ROOT_PATH.'public/admin_uploads/cate_img');
            if($info){
                return $info->getSaveName();

            }else{
                return $info->getError();
            }
        }
    }
    // ajax异步改变栏目状态
    public function changeStatus(){
        if (request()->isAjax()) {
            $id = input('cateid');
            $data = db('cate')->field('status')->where(['id' => $id])->find();
            $status = $data['status'];
            if ($status) {
                db('cate')->where(['id' => $id])->update(['status' => 0]);
                return 1;//显示修改为隐藏
            } else {
                db('cate')->where(['id' => $id])->update(['status' => 1]);
                return 2;//隐藏修改为显示
            }
        }else{
            $this->error('非法操作！');
        }
    }
    //排序和删除
    public function delSort(){
        $data = input("post.");
        foreach ($data['sort'] as $k=>$v){
            db('cate')->where(['id' => $k])->update(['sort' => $v]);
        }
        if ($data['checked']){
            model('Cate')->batchRemove($data['checked']);
        }
        $this->success('操作成功！','lst');
    }
    //单项删除
    public function del(){
        if (request()->isAjax()){
            $id = input('cate_id');
            $ids = model('Cate')->getChildrenIds($id);
            $ids[] = intval($id);
            $del = db('Cate')->delete($ids);
            if ($del){
                $data = [
                    'status'    =>  '600',
                    'data'      =>  'success',
                    'message'   =>  'success',
                ];
                return json_encode($data);
            }else{
                $data = [
                'status'    =>  '601',
                'data'      =>  'error',
                'message'   =>  'error',
                ];
                return json_encode($data);
            }
        }else{
            $this->error('非法操作！');
        }
    }
    //异步撤销图片上传
    public function cancel(){
        if (request()->isAjax()){
            $imgUrl = input('imgUrl');
            $id = input('imgId');
            db('cate')->where('id',$id)->setField('img','');
            $res = @unlink("E:/phpStudy/WWW/cms/public/admin_uploads/cate_img/".$imgUrl);
            return json_encode(['status'=>1,'code'=>200,'data'=>$res]);
        }
    }
}
