<?php
/**
 * Created by PhpStorm.
 * User: leido
 * Date: 2017/9/3
 * Time: 15:58
 */

namespace app\api\controller\v1;

use think\Validate;

class Banner
{
    /**
     * 获取指定Banner的信息
     * @param $id Banner的id号
     * @http GET
     * @url 访问接口的路径
     */
    public function getBanner($id){
        //独立验证
        //验证器

        $data = [
            'name' => 'wandou',
            'email' => 'wandou.qq.com'
        ];

        $validate = new Validate([
            'name' => 'require|max:10',
            'email' => 'email'
        ]);

        $result = $validate->check($data);
        echo $validate->getError();
    }
}