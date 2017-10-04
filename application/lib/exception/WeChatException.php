<?php
/**
 * Created by PhpStorm.
 * User: leidong
 * Date: 2017/9/18
 * Time: 15:00
 */

namespace app\lib\exception;


class WeChatException extends BaseException{
    //HTTP 状态码 400,200
    public $code = 400;

    //错误信息
    public $msg = '微信服务器接口调用失败';

    //错误码
    public $errorCode = 999;
}