<?php
/**
 * Created by PhpStorm.
 * User: leidong
 * Date: 2017/9/18
 * Time: 15:53
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;
use app\api\service\Token as TokenService;

class Token
{
    public static function generateToken(){
        $randChar = getRandChar(32);
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        $tokenSalt = config('secure.token_salt');
        return md5($randChar . $timestamp . $tokenSalt);
    }

    public static function getCurrentTokenVar($key){
        $token = Request::instance()
            ->header('token');
        $vars = Cache::get($token);
        if(!$vars){
            throw new TokenException();
        }
        else{
            if(!is_array($vars)){
                $vars = json_decode($vars, true);
            }
            if(array_key_exists($key, $vars)){
                return $vars[$key];
            }
            else{
                throw new Exception('尝试获取的Token变量并不存在！');
            }
        }
    }

    public static function getCurrentUID(){
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }

    public static function needPrimaryScope(){
        $scope = self::getCurrentTokenVar('scope');
        if ($scope) {
            if ($scope >= ScopeEnum::User) {
                return true;
            }else {
                throw new ForbiddenException();
            }
        }
        else{
            throw new TokenException();
        }
    }

    public static function needExclusiveScope()
    {
        $scope = TokenService::getCurrentTokenVar('scope');
        if ($scope) {
            if ($scope == ScopeEnum::User) {
                return true;
            }else {
                throw new ForbiddenException();
            }
        }
        else{
            throw new TokenException();
        }
    }

    public static function isValidOperate($checkedUID){
        if(!$checkedUID){
            throw new Exception('检测UID时必须传入一个被检测UID');
        }
        $currentOperateUID = self::getCurrentUID();
        if($currentOperateUID == $checkedUID){
            return true;
        }
        return false;
    }

    public static function verifyToken($token){
        $exist = Cache::get($token);
        if($exist){
            return true;
        }
        else{
            return false;
        }
    }
}