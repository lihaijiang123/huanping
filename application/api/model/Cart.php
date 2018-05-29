<?php
namespace app\api\model;

use think\Model;
use think\Db;

class Cart extends Model
{

    //自动写入时间
    protected $autoWriteTimestamp = 'datetime';
    protected $updateTime = false;

    function getMyCart($uid)
    {
        $data = $this->where('uid', $uid)
            ->field('goodsid,create_time,price')
            ->where('status', 'neq', '0')
            ->select();
        return $data;
    }


    /***
     * 添加购物车
     * @param $data 数据
     * @return bool 结果
     */
    function addToCart($data)
    {
        //该用户已经把该商品加入购物车
        $count = $this->where(['uid'=>$data['uid'], 'goodsid'=>$data['goodsid']])->count();
        if ($count > 0) result("40021");
        //加入购物车成功
        return $this->save($data);
    }

    function removeCart($data)
    {
        $res = $this->where(['uid'=>$data['uid'], 'goodsid'=>$data['goodsid']])->update(['status'=>'0']);
        return $res;
    }

    /**
     * 查找商品
     * @param string 商品id
     * @return int 返回改课程的个数
     */
    function findGoods($goodsid)
    {
        return DB::name('flv_category')->where('id', $goodsid)->count();
    }

    /**
     * 查询商品价格
     * @param $goodsid
     * @return string 价格
     */
    function goodsPrice($goodsid)
    {
        $res = DB::name('flv_category')->field('bag_price')->where('id', $goodsid)->find();
        return $res['bag_price'];
    }
}