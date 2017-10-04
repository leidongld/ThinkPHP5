<?php
/**
 * Created by PhpStorm.
 * User: leidong
 * Date: 2017/9/18
 * Time: 21:06
 */

namespace app\lib\exception;


class SuccessMessage
{
    public $code = 201;

    //错误信息
    public $msg = '成功';

    //错误码
    public $errorCode = 0;
}