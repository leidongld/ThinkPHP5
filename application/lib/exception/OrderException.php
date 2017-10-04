<?php
/**
 * Created by PhpStorm.
 * User: leidong
 * Date: 2017/9/19
 * Time: 17:00
 */

namespace app\lib\exception;


class OrderException extends BaseException
{
    public $code = 404;

    public $msg = '订单不存在';

    public $errorCode = 80000;
}