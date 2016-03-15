<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/12/2016
 * Time: 7:04 PM
 */

namespace comradepashka\gallery\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Image
 * @package comradepashka\gallery\models
 * @property string $path
 */

class Image extends ActiveRecord
{
    const STATE_EMPTY = 1;
    const STATE_UNSAVED = 2;
    const STATE_BROKEN = 4;
    const STATE_NORMAL = 5;

    const THUMBS_EMPTY = 1;
    const THUMBS_PARTIAL = 2;
    const THUMBS_ALL = 3;

    public $gallery;

    public static function tableName()
    {
        return 'image';
    }

    public function rules()
    {
        return [
            [['path'], 'required'],
            [['path', 'title', 'description', 'keywords'], 'string', 'max' => 255]
        ];
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => TimestampBehavior::className(),
                'value' => function () {
                    return date('U');
                }
            ]
        ]);
    }

    public function setPath($path)
    {
        if ($tmpImage = Image::find()->where(['path' => $path])) {

            $this->attributes = $tmpImage;
        } else

        $this->path = $path;
    }

    public function getParentPath()
    {
        return preg_replace("/[^\/]+\/$/", "", $this->path);
    }
    public function getName()
    {
        return preg_replace("/[^\/]*\//", "", $this->path);
    }
    public function getShortFileName($length = 18, $suffix = "...")
    {
        return substr($fn = $this->Name, 0, $length) . ((strlen($fn) > $length) ? $suffix : "");
    }
    public function getState()
    {
        if (!$this->path) return Image::STATE_EMPTY;
        if ($this->isNewRecord) return Image::STATE_UNSAVED;
        if (!file_exists($this->path)) return Image::STATE_BROKEN;
        return Image::STATE_NORMAL;
    }

    /*
     *
    $images = Image::find()->where(['rlike', 'path', "^{$path}[^/]+$"])->all();


    public function saveVersions()
    {
//        $imagine = new \Imagine\Imagick\Imagine();
        $imagine = YIImage::getImagine();
        $newImage = $imagine->open($this->getPath());

        foreach ($this->versions as $k => $v) {
            $fn = preg_replace("#(.*)(\.)([^\.]+)$#", "\\1-$k.\\3", $this->getPath());
            if (file_exists($fn)) {
            } else {
                $v($newImage)->save($fn);
            }
        }
    }

    public function removeVersions($version = "ALL")
    {
        foreach ($this->versions as $k => $v) {
            if ($k == $version || $version == "ALL") {
                $fn = preg_replace("#(.*)(\.)([^\.]+)$#", "\\1-$k.\\3", $this->getPath());
                if (file_exists($fn)) {
                    unlink($fn);
                }
            }
        }
        if (file_exists($this->getPath())) {
            unlink($this->getPath());
        }
    }

     */

    public function getImageAuthors()
    {
        return $this->hasMany(ImageAuthor::className(), ['image_id' => 'id']);
    }
    public function getImageSEO()
    {
        return $this->hasMany(ImageSeo::className(), ['image_id' => 'id']);
    }

}