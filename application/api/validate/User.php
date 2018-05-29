<?php
/**
 * Created by PhpStorm.
 * User: li_ha
 * Date: 2018/5/9
 * Time: 11:38
 */
namespace app\api\validate;
use think\Validate;

class User extends Validate {
    /** 规则 **/
    protected $rule =   [
        ['phone', 'require|length:11|/^1[345678][0-9]{9}$/', '40003|40004|40005'],
        ['password', 'require|length:6,16', '40006|40007'],
        ['idnumber','/\d{18}/','40010'],
        ['newpassword', 'require|length:6,16', '40016|40007'],
    ];


    /** 场景设置 **/
    protected $scene = [
        'register'         =>  ['phone','password'],
        'register_detail'  =>  ['idnumber'],
        'login'            =>  ['phone', 'password'],
        'editUserPassword' =>  ['password', 'newpassword'],
        'modifyUserMessage'=>  ['idnumber'],
        'forgetPassword'   =>  ['phone', 'password'],
    ];



}