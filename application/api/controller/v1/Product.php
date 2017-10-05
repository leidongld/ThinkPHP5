<?php
/**
 * Created by PhpStorm.
 * User: leidong
 * Date: 2017/9/16
 * Time: 9:09
 */

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\Product as ProductModel;
use app\api\validate\Count;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ProductException;

class Product extends BaseController
{
    protected $beforeActionList = [
        'checkSuperScope' => ['only' => 'createOne,deleteOne']
    ];

    public function getRecent($count=15){
        (new Count())->goCheck();
        $product =  ProductModel::getMostRecent($count);
        if(!$product){
            throw new ProductException();
        }
        $collection = collection($product);
        $product = $collection->hidden(['summary'])->toArray();
        return $product;
    }

    public function getAllInCategory($id=-1){
        (new IDMustBePositiveInt())->goCheck();
        $products = ProductModel::getProductsByCcategoryID($id);
        if(!$products){
            throw new ProductException();
        }
        $data = $products;
//            ->hidden(['summary'])
            //->toArray();
        return $data;
    }

    public function getOne($id){
        (new IDMustBePositiveInt())->goCheck();
        $product = ProductModel::getProductDetail($id);
        if(!$product){
            throw new ProductException();
        }
        return $product;
    }

    public function deleteOne($id){
        ProductModel::destroy($id);
    }
}