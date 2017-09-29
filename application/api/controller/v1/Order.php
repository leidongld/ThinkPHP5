<?php
/**
 * Created by PhpStorm.
 * User: leido
 * Date: 2017/9/19
 * Time: 13:13
 */

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;
use app\api\validate\OrderPlace;

class Order extends BaseController
{
    //用户在选择商品后，向API提交包含他所选择商品的详细信息
    //API在接收的信息后，需要检查订单相关商品的库存量
    //有库存，返回客户端可以支付，把订单数据存入数据库中。下单成功了，返回客户端消息，高速客户端可以支付了
    //调用支付接口进行支付
    //还需要再次进行库存量检测
    //服务器刁颖微信的支付接口进行支付
    //小程序根据微信返回的结果拉起微信支付
    //微信会返回给我们一个支付的结果（异步）
    //成功，进行库存量的扣除
    //失败，返回支付失败的结果

    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'placeOrder']
    ];

    public function placeOrder(){
        (new OrderPlace())->goCheck();
        $products = input('post.products/a');
        $uid = TokenService::getCurrentUid();

        $order = new OrderService();
        $status = $order->place($uid, $products);
        return $status;
    }


}