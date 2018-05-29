<?php
namespace app\api\model;

use think\Model;
use app\api\controller\Pro;
use think\Session;
use think\Validate;

class User extends Model
{

    /***
     * 注册用户
     * @param $data  需要插入的数据
     * @return mixed 刚添加的uid
     */
    function add($data)
    {
        /**
         * POST的数据
         * @param tel       电话号码(必填)
         * @param password  密码(必填)
         * @param teoken    token(必填)
         * @param model     手机类别 android ios(选填 默认为android)
         * @param wechat    微信号(选填)
         * @param qq        QQ号(选填)
         */


        //数据过滤
        $validate = Validate('User');
        if(!$validate->scene('register')->check($data)){
            //获取验证过滤错误码
            result($validate->getError());
        }
        //密码加密
        $data['password'] = md5($data['password']);
        //保存
        if( $this->save($data) ){
            //返回刚刚插入的id
            return $this->uid;
        }
    }

    /***
     * 登录
     * @param $data 用户数据
     * @return int 结果
     */
    function dologin($data, $header_token)
    {
        //开启数据过滤
        $validata = Validate('User');
        //场景应用login
        if (!$validata->scene('login')->check($data)){
            result($validata->getError());
        }
        //通过传递来的电话号查找用户
        $user = $this->where('phone', $data['phone'])->find();
        if(empty($user)){
            //如果为空则用户不存在
            return 1;
        }else{
            //用户被冻结无法登陆
            if($user['status'] == '-1') return 5;

            $token = create_token();

            //用户状态为已登录的时候
            if($user['status'] == '1'){
                //如果用户token不为空 并且token与数据库对比成功 说明用户无需登陆
                if(!empty($header_token) && $user['token'] == $header_token) {
                    $arr = array(
                        //生成一个新token
                        'token' => $token,
                    );
                    $this->where('phone', $data['phone'])->update($arr);

                    Pro::$message['20003']['data'] = array('token', $token);
                    result('20003');
                //如果token不为空 但是token不一致  说明是在其他手机登录的
                }elseif(empty($header_token) || $user['token'] != $header_token){
                    return 4;
                }
            }
            //用户为未登录状态时
            if($user['status'] == '0'){
                if(md5($data['password']) != $user['password']){
                    //如果密码不正确登录失败
                return 2;
                }else{
                    //如果密码正确则登录
                    $arr = array(
                        'status' => '1',
                        'token'   => $token,
                    );
                    $this->where('phone', $data['phone'])->update($arr);
                    Pro::$message['20003']['data'] = array('token', $token);
                    result('20003');
                }
            }
        }
    }

    /***
     * 退出登录
     * @param $phone 电话号
     * @return int   结果
     */
    function doexit($token)
    {
        if($this->where('token', $token)->update(['status'=>'0', 'token'=>''])){
            return 1;
        }else{
            return 0;
        }
    }

    /***
     * 修改密码
     * @param $data 用户数据
     * @return int 结果
     */
    function editUserPwd($data, $token)
    {
        $validata = Validate('User');
        //场景应用edit user password
        if (!$validata->scene('editUserPassword')->check($data)){
            result($validata->getError());
        }
        //获取旧密码
        $user_data = $this->field('password')->where('token', $token)->find();
        //如果无通过token查找返回的用户信息 则用户不存在
        if (empty($user_data)) request('40002');
        //用户传递过来的密码
        $newpassword = md5($data['password']);

        //如果用户输入的旧密码与数据库一致 执行修改密码
        if ($newpassword == $user_data['password']){
            //执行修改密码
            //修改新密码
            $this->where('token', $token)->update(['password' => md5($data['newpassword'])]);
            result('20005');
        }else{
            result('40017');
        }
    }

    /**
     * 忘记密码
     */
    function forgetPassword($data)
    {

        //查找该用户是否存在
        $user_data = $this->where('phone', $data['phone'])->find();
        //如果无通过手机号查找返回的用户信息 则用户不存在
        if (empty($user_data)) request('40002');
        //执行修改密码
        $this->where('phone', $data['phone'])->update(['password', md5($data['password'])]);
        result('20008');
    }


    /***
     * 获取用户uid
     * @param $data 用户手机号
     * @return mixed 用户uid
     */
    function getUid($token)
    {
        return $this->field('uid')->where('token', $token)->find()['uid'];
    }

    function editPassword($data)
    {
        $user_data = $this->where('phone', $data['phone'])->find();
        if (empty($user_data)) request('40002');
        $this->where('phone', $data['phone'])->update($data);
    }

    /**
     * 查询是否有这个token
     * @param $token
     * @return int
     */


    function hasOneCount($field, $filed_value)
    {
        return $this->where($field, $filed_value)->count();
    }

    /**
     * 一对一
     * @return \think\model\Relation
     */
    public function profile()
    {
        return $this->hasOne('User_detail','uid','uid');
    }

}