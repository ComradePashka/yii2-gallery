<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/12/2016
 * Time: 7:04 PM
 */

namespace comradepashka\gallery\models;


use yii\db\ActiveRecord;

/**
 * Class Image
 * @package comradepashka\gallery\models
 * @property string $path
 */

class Image extends ActiveRecord
{
    public $p;
    public function __construct($path) {
        $this->p = $path;
    }

    public static function tableName()
    {
        return 'image';
    }
    public function getUrl()
    {
        return "URL!";
    }
    public function getFileName()
    {
        return "FileName.jpg";
    }
    public function getShortFileName()
    {
        return "ShortFileName.jpg";
    }

}