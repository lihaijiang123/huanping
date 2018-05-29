<?php
namespace app\api\controller;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $this->fetch();
    }

    public function test()
    {

        $date = array('name'=>'1111111111111111');
        //
        $validate = Validate('Index');

        if(!$validate->scene('add')->check($date)){
            $this->error($validate->getError());
        }

        //把$data 提交给model层

    }
    /**
     * 编辑页面
     */
    public function edit($id=0)
    {
        if(intval($id)<1)
        {
            $this->error('参数不合法');
        }
    }
}
