<?php
/**
 * Created by PhpStorm.
 * User: leido
 * Date: 2017/9/15
 * Time: 11:49
 */

namespace app\api\validate;


class IdConnection extends BaseValidate
{
    protected $rule = [
        'ids' => 'require|checkIDs'
    ];

    protected $message = [
        'ids' => 'ids参数必须是为以都好分割的多个正整数'
    ];

    protected function checkIDs($value){
         $values = explode(',', $value);
         if(empty($values)){
             return false;
         }
         foreach ($values as $id){
             if(!$this->isPositiveInteger($id)){
                 return false;
             }
         }
         return true;
    }
}