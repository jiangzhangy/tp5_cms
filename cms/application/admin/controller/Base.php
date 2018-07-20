<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;

class Base extends Controller
{
    public function _initialize(){
        $request = Request::instance();
        $controller = $request->controller();
        $action  = $request->action();
        $ca = $controller.'/'.$action;
        $this->assign([
            'controller'    =>  $controller,
            'ca'            =>  $ca,
        ]);

    }
}
