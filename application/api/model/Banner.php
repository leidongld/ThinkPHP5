<?php
/**
 * Created by PhpStorm.
 * User: leidong
 * Date: 2017/9/4
 * Time: 20:57
 */

namespace app\api\model;

class Banner extends BaseModel
{
    protected $hidden = ['update_time', 'delete_time'];

    public function items(){
        //关联模型  外键  当前模型的主键
        return $this->hasMany("BannerItem", 'banner_id', 'id');
    }

    public static function getBannerById($id){
        //TODO:根据Banner的id号获取Banner的信息
//        $result = Db::query('select * from banner_item where img_id=?', [3]);
//        return $result;
//        $result = Db::table('banner_item')
//            //->fetchSql()
//            ->where(function ($query) use($id){
//                $query->where('banner_id', '=', '$id');
//            })
//            ->select( );
//        return $result;

        $banner = self::with(['items','items.img'])

            ->find($id);
        return $banner;
    }
}