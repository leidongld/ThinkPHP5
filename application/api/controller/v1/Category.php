<?php
/**
 * Created by PhpStorm.
 * User: leidong
 * Date: 2017/9/16
 * Time: 11:53
 */

namespace app\api\controller\v1;

use app\api\model\Category as CategoryModel;
use app\api\service\Token as TokenService;
use app\lib\exception\CategoryException;

class Category
{
    protected $beforeActionList = [
        'checkPromaryScope' => ['only' => 'getAllCategories']
    ];

    protected function checkPrimaryScope(){
        TokenService::needPrimaryScope();
    }

    public function getAllCategories(){
        $categories = CategoryModel::all([], 'img');
        if(!$categories){
            throw new CategoryException();
        }
        return $categories;
    }
}