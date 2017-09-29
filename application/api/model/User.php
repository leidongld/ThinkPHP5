<?php
/**
 * Created by PhpStorm.
 * User: leido
 * Date: 2017/9/18
 * Time: 12:23
 */

namespace app\api\model;

class User extends BaseModel
{
    public function address(){
        return $this->hasOne('UserAddress', 'user_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany('Order', 'user_id', 'id');
    }

    public static function getByOpenID($openid){
        $user = User::where('openid', '=', $openid)
            ->find();
        return $user;
    }
}