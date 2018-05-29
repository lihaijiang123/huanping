<?php
namespace app\api\model;

use think\Model;

class index extends Model
{
    public function demo()
    {
        $data = [
            'status' => ['eq',-1],
            'id'     => $id,
        ];

        $order = [
            'id'     => 'desc',
        ];

        $result = $this->where($data)
                ->order($order)
                ->paginate();

        return $result;
    }
}