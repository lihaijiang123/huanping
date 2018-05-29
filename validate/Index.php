<?php
/**
 * Created by PhpStorm.
 * User: li_ha
 * Date: 2018/5/9
 * Time: 11:38
 */
namespace app\api\validate;
use think\Validate;

class Index extends Validate {
    protected $rule = [
        ['name', 'require|max:10', '请输入信息|最大不能超过十位'],
    ];

    /**场景设置**/
    protected $scene = [
        'add'           =>  ['name', 'parent_id'],
        'listorder'     =>  ['id'],
    ];
}