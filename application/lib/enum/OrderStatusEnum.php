<?php
/**
 * Created by PhpStorm.
 * User: leidong
 * Date: 2017/9/25
 * Time: 14:34
 */

namespace app\lib\enum;


class OrderStatusEnum
{
    //等待支付
    const UNPAID = 1;

    //已支付
    const PAID = 2;

    //已发货
    const DELIVERED = 3;

    //已支付，但库存不足
    const PAID_BUT_OUT_OF = 4;
}