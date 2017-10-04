<?php
/**
 * Created by PhpStorm.
 * User: leidong
 * Date: 2017/9/18
 * Time: 10:55
 */

namespace app\api\validate;


class TokenGet extends BaseValidate
{
    protected $rule = [
        'code' => 'require|isNotEmpty'
    ];

    protected $message = [
        'code' => '没有Code还想获取Token'
    ];
}