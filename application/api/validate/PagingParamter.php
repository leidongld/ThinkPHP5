<?php
/**
 * Created by PhpStorm.
 * User: leidong
 * Date: 2017/10/1
 * Time: 16:59
 */

namespace app\api\validate;


class PagingParamter extends BaseValidate
{
    protected $rule = [
        'page' => 'isPositiveInteger',
        'size' => 'isPositiveInteger'
    ];

    protected $message = [
        'page' => '分页单数必须是正整数',
        'size' => '分页单数必须是正整数'
    ];
}