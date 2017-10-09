<?php
/**
 * Created by PhpStorm.
 * User: leidong
 * Date: 2017/9/19
 * Time: 13:13
 */

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\OrderPlace;
use app\api\validate\PagingParamter;
use app\api\model\Order as OrderModel;
use app\lib\exception\OrderException;

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

    public function getOrderByUser($page=1, $size=15){
        (new PagingParamter())->goCheck();
        $uid = TokenService::getCurrentUID();
        $pagingOrders = OrderModel::getSummaryByUser($uid, $page, $size);
        if($pagingOrders->isEmpty()){
            return [
                'data' => [],
                'current_page' =>$pagingOrders->getCurrentPage()
            ];
        }
        $data = $pagingOrders->hidden('snap_items','snap_address','prepay_id')
            ->toArray();
        return [
            'data' => $data,
            'current_page' =>$pagingOrders->getCurrentPage()
        ];
    }

    public function getDetail($id){
        (new IDMustBePositiveInt())->goCheck();
        $orderDetail = OrderModel::get($id);
        if(!$orderDetail){
            throw new OrderException();
        }
        return $orderDetail->hidden('prepay_id');
    }

    /**
     * 根据用户id分页获取订单列表（简要信息）
     * @param int $page
     * @param int $size
     * @return array
     * @throws \app\lib\exception\ParameterException
     */
    public function getSummaryByUser($page = 1, $size = 15)
    {
        (new PagingParamter())->goCheck();
        $uid = TokenService::getCurrentUid();
        $pagingOrders = OrderModel::getSummaryByUser($uid, $page, $size);
        if ($pagingOrders->isEmpty())
        {
            return [
                'current_page' => $pagingOrders->currentPage(),
                'data' => []
            ];
        }
//        $collection = collection($pagingOrders->items());
//        $data = $collection->hidden(['snap_items', 'snap_address'])
//            ->toArray();
        $data = $pagingOrders->hidden(['snap_items', 'snap_address'])
            ->toArray();
        return [
            'current_page' => $pagingOrders->currentPage(),
            'data' => $data
        ];

    }

    /**
     * 获取全部订单简要信息（分页）
     * @param int $page
     * @param int $size
     * @return array
     * @throws \app\lib\exception\ParameterException
     */
    public function getSummary($page=1, $size = 20){
        (new PagingParamter())->goCheck();
//        $uid = Token::getCurrentUid();
        $pagingOrders = OrderModel::getSummaryByPage($page, $size);
        if ($pagingOrders->isEmpty())
        {
            return [
                'current_page' => $pagingOrders->currentPage(),
                'data' => []
            ];
        }
        $data = $pagingOrders->hidden(['snap_items', 'snap_address'])
            ->toArray();
        return [
            'current_page' => $pagingOrders->currentPage(),
            'data' => $data
        ];
    }
}