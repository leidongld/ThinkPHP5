<?php
/**
 * Created by PhpStorm.
 * User: leidong
 * Date: 2017/9/18
 * Time: 22:24
 */

namespace app\lib\exception;


class ForbiddenException extends BaseException
{
    public $code = 403;

    //错误信息
    public $msg = '权限不够';

    //错误码
    public $errorCode = 10001;
}