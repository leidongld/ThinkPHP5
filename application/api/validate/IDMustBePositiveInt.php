<?php
/**
 * Created by PhpStorm.
 * User: leidong
 * Date: 2017/9/3
 * Time: 17:10
 */

namespace app\api\validate;


class IDMustBePositiveInt extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger'
    ];

    protected $message = [
        'id' => 'id必须是正整数'
    ];
}