<?php
/**
 * Created by PhpStorm.
 * User: leido
 * Date: 2017/9/16
 * Time: 9:03
 */

namespace app\api\validate;


class Count extends BaseValidate
{
    protected $rule = [
        'count' => 'isPositiveInteger|between:1,20'
    ];
}