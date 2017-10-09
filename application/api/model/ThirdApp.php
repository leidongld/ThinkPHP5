<?php
/**
 * Created by PhpStorm.
 * User: leido
 * Date: 2017/10/9
 * Time: 11:44
 */

namespace app\api\model;

class ThirdApp extends BaseModel
{
    public static function check($ac, $se)
    {
        $app = self::where('app_id','=',$ac)
            ->where('app_secret', '=',$se)
            ->find();
        return $app;

    }
}