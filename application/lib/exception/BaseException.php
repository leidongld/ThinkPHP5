<?php
/**
 * Created by PhpStorm.
 * User: leido
 * Date: 2017/9/4
 * Time: 21:49
 */

namespace app\lib\exception;


use think\Exception;
use Throwable;

class BaseException extends Exception
{
    //HTTP 状态码 400,200
    public $code = 400;

    //错误信息
    public $msg = '参数错误';

    //错误码
    public $errorCode = 10000;

    public function __construct($params = [])
    {
        if(!is_array($params)){
            return ;
            //throw new Exception('Params must be data');
        }
        if(array_key_exists('code', $params)){
            $this->code = $params['code'];
        }
        if(array_key_exists('msg', $params)){
            $this->msg = $params['msg'];
        }
        if(array_key_exists('errorCode', $params)){
            $this->errorCode = $params['errorCode'];
        }
    }
}