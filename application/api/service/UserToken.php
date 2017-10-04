<?php
/**
 * Created by PhpStorm.
 * User: leidong
 * Date: 2017/9/18
 * Time: 12:26
 */

namespace app\api\service;

use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;
use app\lib\exception\WeChatException;
use think\Exception;
use app\api\model\User as UserModel;

class UserToken extends Token
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
                $this->processLoginError($wxResult);
            }
            else{
                return $this->grantToken($wxResult);
            }
        }
    }

    /**
     * 颁发令牌
     * @param $wxResult
     */
    private function grantToken($wxResult){
        //拿到openid
        //数据库里看一下，这个openid是不是已经存在
        //如果存在则不处理，如果不存在新增一条记录
        //生成令牌，准备缓存数据，写入缓存
        //把令牌返回到客户端去
        //key  令牌
        //value  wxResult uid scope
        $openid = $wxResult['openid'];
        $user = UserModel::getByOpenID($openid);
        if($user){
            $uid = $user->id;
        }
        else{
            $uid = $this->newUser($openid);
        }
        $cachedValue = $this->prepareCacheValue($wxResult, $uid);
        $token = $this->saveToCache($cachedValue);
        return $token;
    }

    private function saveToCache($cachedValue){
        $key = self::generateToken();
        $value = json_encode($cachedValue);
        $expire_in = config('setting.token_expire_in');

        $request = cache($key, $value, $expire_in);//缓存
        if(!$request){
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => '10005'
            ]);
        }
        return $key;
    }

    private function prepareCacheValue($wxResult, $uid){
        $cachedValue = $wxResult;
        $cachedValue['uid'] = $uid;
        //16代表客户端用户的权限
        //32代表（CMS）管理员的权限
        $cachedValue['scope'] = ScopeEnum::User;
        return $cachedValue;
    }

    private function newUser($openid){
        $user = UserModel::create([
            'openid' => $openid
        ]);
        return $user->id;
    }

    private function processLoginError($wxResult){
        throw new WeChatException([
            'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode'],
        ]);
    }
}