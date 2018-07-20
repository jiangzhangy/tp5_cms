<?php

//后台配置项
return [
    'template'               => [
        // 模板后缀
        'view_suffix'  => 'htm',
    ],

    // 视图输出字符串内容替换
    'view_replace_str'       => [
        '__ADMIN__'     =>      '/cms/public/static/admin',
    ],
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'         => 'trim',
];

