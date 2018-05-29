<?php
namespace app\api\controller;

use think\Controller;
use think\Request;

/**
 *  APP用户注册
 */
class Pro extends Controller
{
    /** 返回代码 提示信息 数据 **/
    public static $message = array(
        // 不成功系列
        '40001' => array('success'=>'false', 'code'=>'40001', 'message'=>'不允许非POST请求'),
        '40002' => array('success'=>'false', 'code'=>'40002', 'message'=>'用户不存在'),
        '40003' => array('success'=>'false', 'code'=>'40003', 'message'=>'请输入手机号码'),
        '40004' => array('success'=>'false', 'code'=>'40004', 'message'=>'请确认手机号码长度'),
        '40005' => array('success'=>'false', 'code'=>'40005', 'message'=>'手机号码不符合规则'),
        '40006' => array('success'=>'false', 'code'=>'40006', 'message'=>'请输入密码'),
        '40007' => array('success'=>'false', 'code'=>'40007', 'message'=>'密码长度必须在6到26位间'),
        '40008' => array('success'=>'false', 'code'=>'40008', 'message'=>'请输入身份验证TOKEN'),
        '40009' => array('success'=>'false', 'code'=>'40009', 'message'=>'请求错误,token验证不通过'),
        '40010' => array('success'=>'false', 'code'=>'40010', 'message'=>'身份证格式不正确'),
        '40011' => array('success'=>'false', 'code'=>'40011', 'message'=>'密码错误'),
        '40012' => array('success'=>'false', 'code'=>'40012', 'message'=>'改手机号已注册'),
        '40013' => array('success'=>'false', 'code'=>'40013', 'message'=>'您已经退出,请勿重复操作'),
        '40015' => array('success'=>'false', 'code'=>'40015', 'message'=>'该账号已在其他手机登录'),
        '40016' => array('success'=>'false', 'code'=>'40016', 'message'=>'请输入您修改后的密码'),
        '40017' => array('success'=>'false', 'code'=>'40017', 'message'=>'原密码错误'),
        '40018' => array('success'=>'false', 'code'=>'40018', 'message'=>'该账号已被冻结'),
        '40019' => array('success'=>'false', 'code'=>'40019', 'message'=>'操作失败，请重试'),
        '40020' => array('success'=>'false', 'code'=>'40020', 'message'=>'短信验证码错误'),
        //购物车
        '40021' => array('success'=>'false', 'code'=>'40021', 'message'=>'您的购物车已存在该课程'),
        '40022' => array('success'=>'false', 'code'=>'40022', 'message'=>'该视频不存在'),
        '40023' => array('success'=>'false', 'code'=>'40023', 'message'=>'请勿重复操作'),
        '40024' => array('success'=>'false', 'code'=>'40024', 'message'=>'购物车为空'),
        // 成功系列
        '20001' => array('success'=>'true', 'code'=>'20001', 'message'=>'用户注册成功'),
        '20002' => array('success'=>'true', 'code'=>'20002', 'message'=>'修改成功'),
        '20003' => array('success'=>'true', 'code'=>'20003', 'message'=>'登录成功'),
        '20004' => array('success'=>'true', 'code'=>'20004', 'message'=>'退出成功'),
        '20005' => array('success'=>'true', 'code'=>'20005', 'message'=>'密码修改成功'),
        '20008' => array('success'=>'true', 'code'=>'20008', 'message'=>'密码找回成功，请牢记您的新密码'),
        //轮播图成功
        '20006' => array('success'=>'true', 'code'=>'20006'),
        //短信验证码发送成功
        '20007' => array('success'=>'true', 'code'=>'20007', 'message'=>'发送成功'),
        //添加购物车成功
        '20009' => array('success'=>'true', 'code'=>'20009', 'message'=>'添加购物车成功'),
        '20010' => array('success'=>'true', 'code'=>'20010', 'message'=>'移除成功'),
        '20011' => array('success'=>'true', 'code'=>'20011', 'message'=>'查询成功'),
    );

    /**
     * 公共函数 验证每次请求的token是否符合规则
     * 如果是GET请求不需要验证token
     * 如果是非GET需要验证token
     */
    public function __construct($request)
    {
        //不允许非post请求
        noIsPost($request);
        //不是所有的方法都验证token
        $url = strtolower(request()->controller().'/'.request()->action());

        if(!in_array($url, config('noToken'))){

            if(request()->header('token') == ""){
                result('40008');
            }else{
                $res = model('User')->hasOneCount('token', $request->header('token'));
                if ($res <= 0) result('40009');
            }
        }

    }
}