<?php
/**
 * Created by PhpStorm.
 * User: leido
 * Date: 2017/9/4
 * Time: 21:41
 */

namespace app\lib\exception;

use think\Exception;
use think\exception\Handle;
use think\Log;
use think\Request;

class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $errorCode;

    /**
     * @param Exception $e
     * @return \think\response\Json
     */
    public function render(\Exception $e){
        //如果是自定义的异常
        if($e instanceof BaseException){
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        }
        //如果不是自定义的异常
        else{
            if(config('app_debug')){
                return parent::render($e);
            }
            else{
                $this->code = 500;
                $this->msg = '服务器内部错误，不想告诉你';
                $this->errorCode = 999;
                $this->recordErrorLog($e);
            }
        }
        $requesu = Request::instance();
        $result = [
            'msg' => $this->msg,
            'error_code' => $this->errorCode,
            'request_url' => $requesu->url()
        ];
        return json($result, $this->code);
    }

    /**
     * @param Exception $e
     */
    private function recordErrorLog(Exception $e){
        Log::init([
            'type' => 'File',
            'path' => LOG_PATH,
            'level' => ['error']
        ]);
        Log::record($e->getMessage(), 'error');
    }
}