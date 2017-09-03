<?php
/**
 * Created by PhpStorm.
 * User: leido
 * Date: 2017/9/3
 * Time: 17:10
 */

namespace app\api\validate;


use think\Validate;

class IDMastBePositiveInt extends Validate
{
    protected $rule = [
        'id' => 'require|isPositiebInteger'
    ];

    protected function isPositiveInteger($value, $rule='', $data='
    ', $field=''){
        if(is_numeric($value) && is_int($value + 0) && ($value + 0) > 0){
            return true;
        }
        else{
            return $field.'必须是正整数';
        }
    }
}