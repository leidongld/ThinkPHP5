<?php
/**
 * Created by PhpStorm.
 * User: leidong
 * Date: 2017/9/15
 * Time: 15:13
 */

namespace app\lib\exception;


class ThemeException extends BaseException
{
    //HTTP 状态码 400,200
    public $code = 400;

    //错误信息
    public $msg = '参数错误';

    //错误码
    public $errorCode = 10000;
}