<?php
namespace app\admin\validate;
use think\Validate;

class Model extends Validate
{
    //定义验证常量
    protected $regex = [
        'url'   =>  '/^((ht|f)tps?):\/\/[\w\-]+(\.[\w\-]+)+([\w\-\.,@?^=%&:\/~\+#]*[\w\-\@?^=%&\/~\+#])?$/',
    ];
    //需要验证的字段及规则
    protected $rule = [
        'model_name'     =>  'require|chsDash|unique:model|max:25',
        'table_name'     =>  'require|alphaDash|unique:model|max:25',
        'status'         =>  'require|number',
    ];
    //信息提示
    protected $message = [
        'model_name.require'     =>  '模型名称必填',
        'model_name.max'         =>  '模型名称不能超过25个字符',
        'model_name.unique'      =>  '模型名称已存在',
        'model_name.chsDash'     =>  '模型名只能是汉字、字母、数字和下划线_及破折号-',
        'table_name.require'     =>  '附加表名称必填',
        'table_name.alphaDash'   =>  '附加表名称为字母和数字，下划线_及破折号-',
        'table_name.unique'      =>  '附加表名称已存在',
        'table_name.max'         =>  '附加表称不能超过25个字符',
        'status.require'         =>  '启用状态必填',
        'dstatus.number'         =>  '启用状态格式不对',
    ];
    //场景验证:     '场景名'    =>   ['验证名'=>'规则']
    protected $scene = [
        'add'    =>  ['model_name','table_name','status',],
        'edit'   =>  ['model_name','table_name','status',],
    ];
}
