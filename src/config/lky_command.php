<?php
/**
 * Created by Lky_Vendor.
 * User: Yu
 * Date: 2017/12/19 0019
 * Time: 下午 3:25
 */
return [
    'db'=>[
        'model_path'=>'Model',//model存放的路径
        'parent_model'=>'YuModel',//model默认的父级名称
        'not_make_field'=>['created_at','updated_at'],//不需要生成的字段
        'show_timestamps'=>true//model中是否显示时间戳
    ],
    'cache'=>[
        'clear_cache'=>false,//在清除日志的同时是否清除cache(如果使用了cache缓存重要的数据,例如:JSSDK tickt 请慎重)
    ],
    'ctrl'=>[
        //父级ctrl名称
        'parent_controller'=>'YuController',
        //父级ctrl引入的namespace
        'parent_controller_name_space'=>'App\Http\Controllers\YuCtrl',
        //模块儿是否转换为小写,例如:admin=>ADMIN
        'str2lower'=>true,
    ],
    'view'=>[
        'not_show'=>['created_at','updated_at'],//生成界面时候不需要展示出来的字段
    ]
];
