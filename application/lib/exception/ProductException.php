<?php
/**
 * Created by PhpStorm.
 * User: leido
 * Date: 2017/9/16
 * Time: 10:14
 */

namespace app\lib\exception;


class ProductException extends BaseException
{
    //HTTP 状态码 400,200
    public $code = 404;

    //错误信息
    public $msg = '指定的商品不存在，请检查参数';

    //错误码
    public $errorCode = 20000;
}