<?php
/**
 * Created by PhpStorm.
 * User: leidong
 * Date: 2017/9/18
 * Time: 14:27
 */

namespace app\api\model;


class ProductImage extends BaseModel
{
    protected $hidden = ['img_id', 'delete_time', 'product_id'];

    public function imgUrl(){
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}