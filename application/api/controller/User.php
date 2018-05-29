<?php
namespace app\api\controller;


use think\Cache;
use think\Request;
use think\Session;
use think\Validate;


/**
 *  APP用户
 */
class User extends Pro
{
    private $obj;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->obj = model('User');

    }

    /***
     * 用户注册
     * @param Request $request
     */
    public function register(Request $request)
    {
        //查询改手机号的记录为几
        $count = $this->obj->hasOneCount('phone', $request->post('phone'));
        //如果大于1 已注册过
        if ($count > 0) result('40012');
        //如果短信验证码与Cache里的不一致返回验证码错误
        if ($request->post('code') != Cache::get(request()->post('phone'))) result('40020');
        //获取需要插入数据库字段的对应数组
        $user = create_mysqlData(request()->post(), config('user'));
        //添加操作
        $last_id = $this->obj->add($user);
        //如果插入主表数据成功
        if($last_id > 0) {
            $user_detail = create_mysqlData(request()->post(), config('user_detail'));
            $user_detail['uid'] = $last_id;
            //如果详情数据不为空
            if(!empty($user_detail)){
                //执行插入详情表数据
                model('UserDetail')->add($user_detail);
            }
        }

    }

    /***
     * 登录
     * @param Request $request
     */
    public function login(Request $request)
    {
        //登录数据
        $data = $request->post();
        //获取token  没有则为空
        $header_token = $request->header('token');
        //执行登录操作
        $num = $this->obj->dologin($data, $header_token);
        switch ($num)
        {
            //用户不存在
            case 1:
                $code = '40002';
                break;
            //密码错误
            case 2:
                $code = '40011';
                break;
            //该账号已在其他手机登录
            case 4:
                $code = '40015';
                break;
            //该账号已被冻结
            case 5:
                $code = '40018';
                break;

        }
        result($code);
    }

    /***
     * 退出登录
     * @param Request $request
     */
    public function doexit(Request $request)
    {

        $token = $request->header('token');
        $res = $this->obj->doexit($token);
        if ($res){
            result('20004');
        }else{
            result('40013');
        }
    }


    /**
     * 修改用户信息
     * @param Request $request
     */
    public function modifyUserMessage(Request $request)
    {
        $data = $request->post();
        //数据验证
        $validata = Validate('User');
        //场景应用
        if (!$validata->scene('modifyUserMessage')->check($data)){
            result($validata->getError());
        }
        //从主表获取用户uid
        $uid = $this->obj->getUid($request->header('token'));

        $data = create_mysqlData($data, config('user_detail'));

        //通过uid修改用户信息
        $result = model('UserDetail')->modifyUserMessage($uid, $data);
        $result ? result('20002'):result('40019');
    }

    /***
     * 修改用户密码
     * @param Request $request
     */
    public function editUserPwd(Request $request)
    {
        //获取post的数据
        $data = $request->post();
        //用户修改自己的密码 需要传递旧密码
        $this->obj->editUserPwd($data, $request->header('token'));
    }

    /**
     * 忘记密码
     * @param Request $request
     */
    public function forgetPassword(Request $request)
    {
        //如果短信验证码与Cache里的不一致返回验证码错误 , 如果验证码错误直接return错误代码
        check_code($request->post('code'), $request->post('phone'));

        /** 通过上一步 **/

        $validata = Validate('User');
        //场景应用edit user password
        if (!$validata->scene('forgetPassword')->check($data)){
            result($validata->getError());
        }

        //获取新的密码
        $data = array(
            'password'    => $request->post('newpassword'),
            'phone'       => $request->post('phone'),
        );
        $this->obj->forgetPassword($data);
    }

    /**
     * 测试一对一关联表
     */
    public function demo()
    {
        $arr = array('name'=>'lihaijiang');

        $user = $this->obj->get();
        dump($user->profile()->save($arr));
    }
}