<?php
/**
 * Created by PhpStorm.
 * User: leidong
 * Date: 2017/9/3
 * Time: 16:42
 */

namespace app\api\validate;


class TestValidate extends BaseValidate
{
    protected $rule = [
        'name' => 'require|max:10',
        'email' => 'require|email'
    ];
}