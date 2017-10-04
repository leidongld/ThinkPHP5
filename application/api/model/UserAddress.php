<?php
/**
 * Created by PhpStorm.
 * User: leidong
 * Date: 2017/9/18
 * Time: 21:31
 */

namespace app\api\model;


class UserAddress extends BaseModel
{
    protected $hidden = ['id', 'delete_time', 'user_id'];
}