<?php
/**
 * Created by PhpStorm.
 * User: leidong
 * Date: 2017/9/3
 * Time: 15:58
 */

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\IDMustBePositiveInt;
use app\api\model\Banner as BannerModel;
use app\lib\exception\BannerMissException;

class Banner extends BaseController
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
        (new IDMustBePositiveInt())->goCheck();
        //$banner = BannerModule::with(['items', 'items.img'])->find($id);
        $banner = BannerModel::getBannerById($id);
        if(!$banner){
            //throw new BannerMissException();
            throw new BannerMissException();
        }
        $c = config('setting.img_prefix');
        return $banner;
    }

    public function showHello(){
        return 'Hello World.';
    }
}