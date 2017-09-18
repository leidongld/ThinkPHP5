<?php
/**
 * Created by PhpStorm.
 * User: leidong
 * Date: 2017/9/12
 * Time: 15:40
 */

namespace app\api\model;


class Product extends BaseModel
{
    protected $hidden = [
        'delete_time', 'update_time', 'pivot', 'from', 'category_id', 'create_time', 'main_img_id'
    ];

    public static function getMostRecent($count){
        $product = self::limit($count)
            ->order('create_time desc')
            ->select();
        return $product;
    }

    public static function getProductsByCcategoryID($categoryID){
        $products = self::where('category_id', '=', $categoryID)
            ->select();
        return $products;
    }

    public static function getProductDetail($id){
        $product = self::with('imgs,properties')
        ->find($id);
        return $product;
    }

    public function properties(){
        return $this->hasMany('ProductProperty', 'product_id', 'id');
    }

    public function imgs(){
        return $this->hasMany('ProductImage', 'product_id', 'id');
    }
}