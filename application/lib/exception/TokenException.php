<?php
/**
 * Created by PhpStorm.
 * User: leidong
 * Date: 2017/9/18
 * Time: 17:50
 */

namespace app\lib\exception;


class TokenException extends BaseException
{
    //HTTP 状态码 400,200
    public $code = 401;

    //错误信息
    public $msg = 'Token已过期或无效';

    //错误码
    public $errorCode = 10001;
}