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
        'show_timestamps'=>true
    ]
];
