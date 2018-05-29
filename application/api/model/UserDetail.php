<?php
/**
 * Created by PhpStorm.
 * User: li_ha
 * Date: 2018/5/16
 * Time: 14:05
 */
namespace app\api\model;

use think\Model;
use app\api\controller\Pro;

class UserDetail extends Model
{
    //自动写入时间
    protected $autoWriteTimestamp = 'datetime';
    protected $updateTime = false;
    /***
     * @param $data  需要插入的数据
     * @return bool  结果
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
        if(!$validate->scene('register_detail')->check($data)){
            result($validate->getError());
        }
//        dump($data);die;
        if( $this->save($data) ){
            result('20001');
        }
    }

    /***
     * 修改用户信息
     * @param $uid 用户uid
     * @return bool 修改结果
     */
    function modifyUserMessage($uid, $data)
    {
        $res = $this->where('uid', $uid)
            ->update($data);
        return $res;
    }
}