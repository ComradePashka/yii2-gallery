<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 5/9/2016
 * Time: 12:38 AM
 */

namespace comradepashka\gallery\models;

use yii\db\ActiveQuery;
use creocoder\taggable\TaggableQueryBehavior;

class ImageQuery extends ActiveQuery
{
    public function behaviors()
    {
        return [
            TaggableQueryBehavior::className(),
        ];
    }
}