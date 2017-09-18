<?php
/**
 * Created by PhpStorm.
 * User: leido
 * Date: 2017/9/18
 * Time: 10:55
 */

namespace app\api\validate;


class TokenGet extends BaseValidate
{
    public $rule = [
        'code' => 'require|isNotEmpty'
    ];

    public $message = [
        'code' => '没有Code还想获取'
    ];
}