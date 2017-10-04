<?php
/**
 * Created by PhpStorm.
 * User: leidong
 * Date: 2017/9/20
 * Time: 20:22
 */

namespace app\api\model;


class Order extends BaseModel
{
    protected $hidden = ['user_id', 'delete_time', 'update_time'];

    //自动写入时间戳
    protected $autoWriteTimestamp = true;

    public function getSnapItemsAttr($value)
    {
        if(empty($value)){
            return null;
        }
        return json_decode($value);
    }

    public function getSnapAddressAttr($value){
        if(empty($value)){
            return null;
        }
        return json_decode(($value));
    }

    public static function getSummaryByUser($uid, $page=1, $size=15)
    {
        $pagingData = self::where('user_id', '=', $uid)
            ->order('create_time desc')
            ->paginate($size, true, ['page' => $page]);
        return $pagingData ;
    }

    public static function getSummaryByPage($uid, $page=1, $size=20){
        $pagingData = self::where('user_id','=',$uid)
            ->order('create_time desc')
            ->paginate($size, true, ['page' => $page]);
        return $pagingData ;
    }

    public function products()
    {
        return $this->belongsToMany('Product', 'order_product', 'product_id', 'order_id');
    }

    public function getSnapItemAttr($value){
        if(empty($value)){
            return null;
        }
        return json_encode($value);
    }
}