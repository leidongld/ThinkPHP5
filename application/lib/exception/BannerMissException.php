<?php
/**
 * Created by PhpStorm.
 * User: leido
 * Date: 2017/9/4
 * Time: 21:54
 */

namespace app\lib\exception;


class BannerMissException extends BaseException
{
    public $code = 404;

    public $msg = '请求的Banner不存在';

    public $errorCode = 40000;
}