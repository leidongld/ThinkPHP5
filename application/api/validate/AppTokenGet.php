<?php
/**
 * Created by PhpStorm.
 * User: leido
 * Date: 2017/10/9
 * Time: 11:32
 */

namespace app\api\validate;


class AppTokenGet extends BaseValidate
{
    protected $rule = [
        'ac' => 'require|isNotEmpty',
        'se' => 'require|isNotEmpty'
    ];
}