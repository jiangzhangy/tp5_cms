<?php
namespace app\admin\validate;
use think\Validate;

class Conf extends Validate
{
    //定义验证常量
    protected $regex = [
        'url'   =>  '/^((ht|f)tps?):\/\/[\w\-]+(\.[\w\-]+)+([\w\-\.,@?^=%&:\/~\+#]*[\w\-\@?^=%&\/~\+#])?$/',
    ];
    //需要验证的字段及规则
    protected $rule = [
        'cname'     =>  'require|chsDash|unique:conf|max:25',
        'ename'     =>  'require|alphaDash|unique:conf|max:25',
        'dt_type'   =>  'require|integer',
        'cf_type'   =>  'require|integer',
    ];
    //信息提示
    protected $message = [
        'cname.require'     =>  '中文名称必填',
        'cname.max'         =>  '中文名称不能超过25个字符',
        'cname.unique'      =>  '中文名称已存在',
        'cname.chsDash'     =>  '中文名只能是汉字、字母、数字和下划线_及破折号-',
        'ename.require'     =>  '英文名称必填',
        'ename.alphaDash'   =>  '英文名称为字母和数字，下划线_及破折号-',
        'ename.unique'      =>  '英文名称已存在',
        'ename.max'         =>  '英文名称不能超过25个字符',
        'dt_type.require'   =>  '展示类型必须',
        'dt_type.integer'   =>  '展示类型格式不对',
        'cf_type.integer'   =>  '配置分类格式不对',
        'cf_type.require'   =>  '配置分类必须',
    ];
    //场景验证:     '场景名'    =>   ['验证名'=>'规则']
    protected $scene = [
        'add'   =>  ['cname','ename','dt_type','cf_type'],
        'edit'  =>  ['cname','ename','dt_type','cf_type'],
    ];
}
