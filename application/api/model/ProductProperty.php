<?php
/**
 * Created by PhpStorm.
 * User: leidong
 * Date: 2017/9/18
 * Time: 14:28
 */

namespace app\api\model;


class ProductProperty extends BaseModel
{
    protected $hidden = ['product_id', 'delete_time', 'id'];

}