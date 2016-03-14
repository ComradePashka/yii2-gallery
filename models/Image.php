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
    public $path;

    public static function tableName()
    {
        return 'image';
    }
    public function getUrl()
    {
        return $this->path;
    }
    public function getFileName()
    {
        return preg_replace("/.*\/(.+)$/", "\\1", $this->path);
    }
    public function getShortFileName($length = 18, $suffix = "...")
    {
        return substr($fn = $this->getFileName(), 0, $length) . ((strlen($fn) > $length) ? $suffix : "");
    }
}