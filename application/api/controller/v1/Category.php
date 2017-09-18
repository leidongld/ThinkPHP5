<?php
/**
 * Created by PhpStorm.
 * User: leido
 * Date: 2017/9/16
 * Time: 11:53
 */

namespace app\api\controller\v1;

use app\api\model\Category as CategoryModel;

class Category
{
    public function getAllCategories(){
        $categories = CategoryModel::all([], 'img');
        return $categories;
    }
}