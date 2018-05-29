<?php
/**
 * Created by PhpStorm.
 * User: li_ha
 * Date: 2018/5/15
 * Time: 19:24
 */
namespace app\api\controller;
use SendMsm\Msm;
use think\Cache;
use think\Request;


/***
 * 首页
 * Class Index
 * @package app\api\controller
 */
class Index extends Pro {

    private $obj;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->obj = model('PlugList');
    }

    /***
     * 首页轮播图
     * @param Request $request
     * @return string
     */
    public function advert(){
        $res = $this->obj->showAdvert();
        Pro::$message['20006']['data'] = $res;
        result('20006');

    }

    /***
     * 发送短信验证码
     */
    public function sendMessage(Request $request){

        $phone = $request->post('phone');

        //开启数据过滤
        $validata = Validate('User');
        //场景应用login
        if (!$validata->scene('login')->check(request()->post())){
            result($validata->getError());
        }

        //随机4位验证码
        $code = rand('1000', '9999');


        //验证码存入cache便于验证
        Cache::set($phone, $code);

        $content = "您好,您的验证码为【 ".$code." 】,请勿透露给其他人！";

        $res = Msm::send($phone, $content);
        if (!$res['flag']){
            die($res['message']);
        }else{
            Pro::$message['20007']['data'] = ['code'=>$code];
            result('20007');
        }
    }

}
