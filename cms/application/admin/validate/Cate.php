<?php
namespace app\admin\validate;
use think\Validate;

class Cate extends Validate
{
    //定义验证常量
    protected $regex = [
        'url'   =>  '/^((ht|f)tps?):\/\/[\w\-]+(\.[\w\-]+)+([\w\-\.,@?^=%&:\/~\+#]*[\w\-\@?^=%&\/~\+#])?$/',
    ];
    //需要验证的字段及规则
    protected $rule = [
        'model_id'  =>  'require|number',
        'pid'       =>  'require|number',
        'cate_attr'   =>  'require|number',
        'cate_name'   =>  'require|max:50|unique:cate',
    ];
    //信息提示
    protected $message = [
        'model_id.require'  =>  '模型必选',
        'model_id.number'   =>  '模型为数字',
        'pid.require'       =>  '上级必选',
        'pid.number'        =>  '上级为数字',
        'cate_attr.require' =>  '栏目属性必选',
        'cate_attr.number'  =>  '栏目属性为数字',
        'cate_name.require' =>  '栏目名称必填',
        'cate_name.unique'  =>  '已存在的栏目名称',
        'cate_name.max'     =>  '栏目名称不能超过50个字符',
    ];
    //场景验证:     '场景名'    =>   ['验证名'=>'规则']
    protected $scene = [
        'add'   =>  ['model_id','pid','cate_attr','cate_name'],
        'edit'   =>  ['model_id','pid','cate_attr','cate_name'],
    ];
}
