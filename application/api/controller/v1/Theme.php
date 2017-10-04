<?php
/**
 * Created by PhpStorm.
 * User: leidong
 * Date: 2017/9/12
 * Time: 15:36
 */

namespace app\api\controller\v1;


use app\api\validate\IdConnection;
use app\api\model\Theme as ThemeModel;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ThemeException;

class Theme
{
    /**
     * @url /theme?ids=id1,id2......
     * @return 一组theme模型|false|\PDOStatement|string|\think\Collection
     */
    public function getSimpleList($ids=''){
        (new IdConnection())->goCheck();
        $ids = explode(',', $ids);
        $result = ThemeModel::with('topicImg,headImg')
            ->select($ids);
        if(!$result){
            throw new ThemeException();
        }
        return $result;
    }

    /**
     * @url /theme/id
     */
    public function getComplexOne($id){
        (new IDMustBePositiveInt())->goCheck();
        $theme = ThemeModel::getThemeWithProducts($id);
        if(!$theme){
            throw new ThemeException();
        }
        return $theme;
    }
}