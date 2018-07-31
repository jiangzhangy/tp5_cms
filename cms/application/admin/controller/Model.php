<?php
namespace app\admin\controller;

use think\Db;
class Model extends Base
{
    //模型列表
    public function lst()
    {
        $modelRes = db('model')->order('id desc')->paginate(2);
        $prefix = config("database.prefix");
        $this->assign([
            'modelRes'  =>  $modelRes,
            'prefix'    =>  $prefix,
        ]);
        return view();
    }
    //添加模型
    public function add()
    {
        if (request()->isPost()){
            $data = input('post.');
            //数据验证
            $validate = validate('Model');
            $result = $validate->scene('add')->check($data);
            if(!$result){
                $this->error($validate->getError());
            }
            $res = db('model')->insertGetId($data);
            if ($res){

                try{
                    $tableName = config("database.prefix").$data['table_name'];
                    $sql = "create table {$tableName} (aid int unsigned not null) engine=MYISAM default charset=utf8";
                    $dbRes = Db::execute($sql);
                }
                catch(\Exception $e){
                    //echo $e->getMessage();die;
                    //todo 是否需要记录日志
                    $tablesName = config("database.prefix")."model";
                    $sql = "DELETE FROM `{$tablesName}` WHERE `id` = $res";
                    Db::execute($sql);
                    $this->error('建表失败，已回滚！');
                }
                $this->success('模型添加成功！','lst');
            }else{
                $this->error('模型添加失败！');
            }
        }
        return view();
    }
    // ajax异步改变模型启用状态
    public function changeStatus(){
        if (request()->isAjax()) {
            $id = input('modelId');
            $data = db('Model')->field('status')->where(['id' => $id])->find();
            $status = $data['status'];
            if ($status) {
                db('Model')->where(['id' => $id])->update(['status' => 0]);
                return 1;//启用修改为禁用
            } else {
                db('Model')->where(['id' => $id])->update(['status' => 1]);
                return 2;//禁用修改为启用
            }
        }else{
            $this->error('非法操作！');
        }
    }
    //ajax异步删除模型
    public function del(){
        if (request()->isAjax()){
            $modelId = input("modelId");
            $tableName = input("tableName");
            $del = db('Model')->delete($modelId);
            $sql = "DROP TABLE {$tableName}";
            DB::execute($sql);
            return json_encode(['status'=>1,'code'=>200,'data'=>$del]);
        }else{
            $this->error('非法操作！');
        }
    }
    //修改模型
    public function edit(){
        $id = input('modelId');
        $models = model('Model')->findOrFail($id);
        if (request()->isPost()){
            $data = input('post.');
            //数据验证
            $validate = validate('Model');
            $result = $validate->scene('edit')->check($data);
            if(!$result){
                $this->error($validate->getError());
            }
            if ($data['oldTableName'] != $data['table_name']){
                $oldTableName = config("database.prefix").$data['oldTableName'];
                $newTableName = config("database.prefix").$data['table_name'];
                $sql = "ALTER TABLE {$oldTableName} RENAME  {$newTableName}";
                DB::execute($sql);
            }
            $res = model('Model')->allowField(true)->isUpdate(true)->save($data);
            if ($res){
                $this->success('修改成功！','lst');
            }else{
                $this->error('修改失败！');
            }
            return ;

        }
        $this->assign([
            'models'    =>  $models,
        ]);
        return view();
    }
}
