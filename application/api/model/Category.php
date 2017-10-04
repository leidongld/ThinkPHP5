<?php
/**
 * Created by PhpStorm.
 * User: leidong
 * Date: 2017/9/16
 * Time: 12:00
 */

namespace app\api\model;


class Category extends BaseModel
{
     

    public function img(){
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }
}