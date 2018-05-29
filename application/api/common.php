<?php

use app\api\controller\Pro;

/**
 * 如果不是POST请求
 * @param $request
 */
function noIsPost($request)
{
    /** 如果不是post请求 **/
    if(!$request->isPost()){
        //exit 不可以换成return 不然不能正常返回
        result('40001');
    }
}


/***
 * 返回状态码信息
 * @param $code 状态码
 * @param bool $type json类型
 */
function result($code, $type = true)
{
    //exit 不可以换成return 不然不能正常返回
    exit(json_encode(Pro::$message[$code], $type));
}

/**
 * 客户端的token与服务器token对比 , 如不一致则不通过
 * @param $salt 盐值
 * @param $token 客户端生成的token
 * @return true or false
 */



/***
 * 一个数组我取出想要的键值对组成一个新的数组
 * @param $data  原数组
 * @param $table 想要的数组键名
 * @return array 得到的数组
 */
function create_mysqlData($data, $table)
{
    $arr = array();

    foreach ($data as $k => $v){
        if(in_array($k, $table)){
            $arr[$k] = $v;
        }
    }

    return $arr;
}

/**
 * 检测验证码是否和服务器端一致
 * @param $code 验证码
 */
function check_code($code, $phone)
{
    if ($code != \think\Cache::get($phone) || $code == null ){
        result('40020');
    }
}

/**
 * 创建token
 * @return string $token
 */
function create_token()
{
    return md5(uniqid().config('salt'));
}
