<?php
/**
 * Created by PhpStorm.
 * User: leido
 * Date: 2017/9/7
 * Time: 18:04
 */

namespace app\lib\exception;


class ParameterException extends BaseException
{
    //HTTP 状态码 400,200
    public $code = 400;

    //错误信息
    public $msg = '参数错误';

    //错误码
    public $errorCode = 10000;
}