<?php
//配置文件
return [

    //盐
    'salt'           =>  'boao',
    //USER主表字段
    'user'           =>  array('phone', 'password', 'token'),
    //USER详情表字段
    'user_detail'    =>  array('uid', 'idnumber', 'create_time', 'enterprise_id', 'wachat', 'qq', 'name', 'avatar'),
    //购物车表字段
    'cart'           =>  array('goodsid', 'count', 'price'),

    //缓存
    'cache'          => [
                            'type'   => 'File',
                            'path'   => CACHE_PATH,
                            'prefix' => '',
                            'expire' => 60,//验证码60秒有效
                        ],

    //不需要Token验证的controller . action    注意全小写
    'noToken'        =>  ['index/sendmessage', 'user/register', 'user/login', 'user/forgetpassword'],
];