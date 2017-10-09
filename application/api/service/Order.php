<?php
/**
 * Created by PhpStorm.
 * User: leidong
 * Date: 2017/9/19
 * Time: 14:04
 */

namespace app\api\service;

use app\api\model\OrderProduct;
use app\api\model\Product;
use app\api\model\UserAddress;
use app\lib\exception\OrderException;
use app\lib\exception\UserException;
use think\Exception;
use think\Db;
use app\api\model\Order as OrderModel;

class Order
{
    //订单的商品列表，也就是客户端传递过来的products参数
    protected $oProducts;

    //真实的商品信息（包括库存量）
    protected $products;

    protected $uid;

    function __construct()
    {
    }

    public function place($uid, $oProducts){
        //oProducts与products做对比
        //products从数据库中查询出来
        $this->oProducts = $oProducts;
        $this->products = $this->getProductsByOrder($oProducts);
        $this->uid = $uid;
        $status = $this->getOrderStatus();
        if(!$status['pass']){
            $status['order_id'] = -1;
            return $status;
        }

        //开始创建订单了
        $orderSnap = $this->snapOrder($status);
        $order = $this->createOrder($orderSnap);
        $order['pass'] = true;
        return $order;

    }

    /**
     * 生成订单快照
     */
    private function snapOrder($status){
        // status可以单独定义一个类
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatus' => [],
            'snapAddress' => json_encode($this->getUserAddress()),
            'snapName' => $this->products[0]['name'],
            'snapImg' => $this->products[0]['main_img_url'],
        ];

        if (count($this->products) > 1) {
            $snap['snapName'] .= '等';
        }


        for ($i = 0; $i < count($this->products); $i++) {
            $product = $this->products[$i];
            $oProduct = $this->oProducts[$i];

            $pStatus = $this->snapProduct($product, $oProduct['count']);
            $snap['orderPrice'] += $pStatus['totalPrice'];
            $snap['totalCount'] += $pStatus['count'];
            array_push($snap['pStatus'], $pStatus);
        }
        return $snap;
    }

    public function createOrder($snap){
        Db::startTrans();
        try {
            $orderNo = $this->makeOrderNo();
            $order = new OrderModel();

            $order->user_id = $this->uid;
            $order->order_no = $orderNo;
            $order->total_price = $snap['orderPrice'];
            $order->total_count = $snap['totalCount'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_name = $snap['snapName'];
            $order->snap_address = $snap['snapAddress'];
            $order->snap_items = json_encode($snap['pStatus']);

            $order->save();

            $orderID = $order->id;
            $create_time = $order->create_time;

            foreach ($this->oProducts as &$p) {
                $p['order_id'] = $orderID;
            }
            $orderProduct = new OrderProduct();
            $orderProduct->saveAll($this->oProducts);
            Db::commit();
            return [
                'order_no' => $orderNo,
                'order_id' => $orderID,
                'create_time' => $create_time
            ];
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public static function makeOrderNo()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn =
            $yCode[intval(date('Y')) - 2017] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        return $orderSn;
    }

    private function snapProduct($product, $oCount)
    {
        $pStatus = [
            'id' => null,
            'name' => null,
            'main_img_url'=>null,
            'count' => $oCount,
            'totalPrice' => 0,
            'price' => 0
        ];

        $pStatus['counts'] = $oCount;
        // 以服务器价格为准，生成订单
        $pStatus['totalPrice'] = $oCount * $product['price'];
        $pStatus['name'] = $product['name'];
        $pStatus['id'] = $product['id'];
        $pStatus['main_img_url'] =$product['main_img_url'];
        $pStatus['price'] = $product['price'];
        return $pStatus;
    }

    private function getUserAddress(){
        $userAddress = UserAddress::where('user_id', '=', $this->uid)
            ->find();
        if(!$userAddress){
            throw new UserException([
                'msg' => '用户收获地址不存在，下单失败',
                'errorCode' => 60001
            ]);
        }
        return $userAddress->toArray();
    }

    //根据订单查收真实的products信息
    private function getProductsByOrder($oProducts){
        $oPIDs = [];
        foreach ($oProducts as $item) {
            array_push($oPIDs, $item['product_id']);
        }
        $products = Product::all($oPIDs);
           //->visible(['id','price','stock','name','main_img_url'])
           //->toArray();
        return $products;
    }

    public function getOrderStatus(){
        $status = [
            'pass' => true,
            'totalCount' => 0,
            'orderPrice' => 0,
            'pStatusArray' => []
        ];

        foreach ($this->oProducts as $oProduct) {
            $pStatus = $this->getProductStatus(
                $oProduct['product_id'], $oProduct['count'], $this->products
            );
            if(!$pStatus['haveStock']){
                $status['pass'] = false;
            }
            $status['orderPrice'] += $pStatus['totalPrice'];
            //$status['totalCount'] += $pStatus['totalCount'];
            array_push($status['pStatusArray'], $pStatus);
        }
        return $status;
    }

    private function getProductStatus($oPID, $oCount, $products){
        $pIndex = -1;

        $pStatus = [
            'id' => null,
            'haveStock' => false,
            'count' => 0,
            'name' => '',
            'price' => 0,
            'totalPrice' => 0
        ];

        for($i = 0; $i < count($products); $i++){
            if($oPID == $products[$i]['id']){
                $pIndex = $i;
            }
        }

        if($pIndex == -1){
            throw new OrderException([
                'msg' => 'id为'.$oPID.'的商品不存在，创建订单失败'
            ]);
        }
        else{
            $product = $products[$pIndex];
            $pStatus['id'] = $product['id'];
            $pStatus['name'] = $product['name'];
            //$pStatus['count'] = $oCount;
            $pStatus['counts'] = $oCount;
            $pStatus['price'] = $product['price'];
            $pStatus['main_img_url'] = $product['main_img_url'];
            $pStatus['totalPrice'] = $product['price'] * $oCount;
            if($product['stock'] - $oCount >= 0){
                $pStatus['haveStock'] = true;
            }
        }
        return $pStatus;
    }

    public function getOrderStock($orderID){
        $oProducts = OrderProduct::where('order_id','=', $orderID)
            ->select();
        $this->oProducts = $oProducts;

        $this->products = $this->getProductsByOrder($oProducts);
        $status = $this->getOrderStatus();
        return $status;
    }

    public function checkOrderStock($orderID)
    {
        //        if (!$orderNo)
        //        {
        //            throw new Exception('没有找到订单号');
        //        }

        // 一定要从订单商品表中直接查询
        // 不能从商品表中查询订单商品
        // 这将导致被删除的商品无法查询出订单商品来
        $oProducts = OrderProduct::where('order_id', '=', $orderID)
            ->select();
        $this->products = $this->getProductsByOrder($oProducts);
        $this->oProducts = $oProducts;
        $status = $this->getOrderStatus();
        return $status;
    }
}