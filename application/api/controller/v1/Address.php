<?php
/**
 * Created by PhpStorm.
 * User: leidong
 * Date: 2017/9/18
 * Time: 19:21
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\UserAddress;
use app\api\validate\AddressNew;
use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;

class Address extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'createOrUpdateAddress,getUserAddress']
    ];

    public function createOrUpdateAddress(){
        $validate = new AddressNew();
        $validate->goCheck();
        //根据Token获取用户的uid
        //根据uid查找用户数据，判断用户是否存在，如果不存在，抛出异常
        //获取用户从客户端提交来的地址信息
        $uid = TokenService::getCurrentUID();
        $user = UserModel::get($uid);
        if(!$user){
            throw new UserException([
                'code' => 404,
                'msg' => '用户收货地址不存在',
                'errorCode' => 60001
            ]);
        }

        $dataArray = $validate->getDataByRule(input('post.'));

        $userAddress = $user->address;
        if(!$userAddress){
            $user->address()->save($dataArray);
        }
        else{
            $user->address->save($dataArray);
        }
        return new SuccessMessage();
    }

    /**
     * 获取用户地址信息
     * @return UserAddress
     * @throws UserException
     */
    public function getUserAddress(){
        $uid = TokenService::getCurrentUid();
        $userAddress = UserAddress::where('user_id', $uid)
            ->find();
        if(!$userAddress){
            throw new UserException([
                'msg' => '用户地址不存在',
                'errorCode' => 60001
            ]);
        }
        return $userAddress;
    }
}