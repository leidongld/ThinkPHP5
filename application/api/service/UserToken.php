<?php
/**
 * Created by PhpStorm.
 * User: leido
 * Date: 2017/9/18
 * Time: 12:26
 */

namespace app\api\service;


use think\Exception;

class UserToken
{
    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    function __construct($code)
    {
        $this->code = $code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(config('wx.login_url'), $this->wxAppID, $this->wxAppSecret, $this->code);
    }

    public function getToken($code=''){
        (new TokenGet())->goCheck();
        $ut = new UserToken();
        $token = $ut->get($code);
        return $token;
    }

    public function get(){
        $result = curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result, true);
        if(empty($wxResult)){
            throw new Exception('获取sessionKey及openID时异常，微信内部错误');
        }
        else{
            $loginFail = array_key_exists('errorCode', $wxResult);
            if($loginFail){

            }
            else{

            }
        }
    }

    private function processLoginError($wxResult){

    }
}