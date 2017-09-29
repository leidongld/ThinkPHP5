<?php
/**
 * Created by PhpStorm.
 * User: leido
 * Date: 2017/9/18
 * Time: 20:46
 */

namespace app\lib\exception;


class UserException extends BaseException
{
    //HTTP 状态码 400,200
    public $code = 404;

    //错误信息
    public $msg = '用户不存在';

    //错误码
    public $errorCode = 60000;
}