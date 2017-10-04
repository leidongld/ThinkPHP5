<?php
/**
 * Created by PhpStorm.
 * User: leido
 * Date: 2017/9/25
 * Time: 10:57
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\WxNotify;
use app\api\validate\IDMustBePositiveInt;
use app\api\service\Pay as PayService;

class Pay extends BaseController
{
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'getPreOrder']
    ];

    public function getPreOrder($id=''){
        (new IDMustBePositiveInt())->goCheck();
        $pay = new PayService($id);
        return $pay->pay();
    }

    public function receiveNotify(){
        //通知频率为15/15/30/180/1000/1000/1000/3600

        //1.检查库存量
        //2.更新这个订单的status状态
        //3.减库存
        //如果成功处理，返回成功的通知，否则返回失败通知
        //特点：post
        $notify = new WxNotify();
        $notify->Handle();
//        $xmlData = file_get_contents('php://input');
//        $result = curl_post_raw('http://leidong.cn/api/v1/pay/re_notify?');
    }
}