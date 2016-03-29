<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/12/2016
 * Time: 7:04 PM
 */

namespace comradepashka\gallery\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\imagine\Image as YiiImage;

use comradepashka\gallery\Module;
use creocoder\translateable\TranslateableBehavior;


/**
 * Class Image
 * @package comradepashka\gallery\models
 * @property string $path
 * @property integer $id
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
            'translateable' => [
                'class' => TranslateableBehavior::className(),
                'translationAttributes' => ['title', 'description', 'keywords', 'header'],
            ],
            [
                'class' => TimestampBehavior::className(),
                'value' => function () {
                    return date('U');
                }
            ]
        ]);
    }

    /*
     * cloning routine. as sample of hack
        public function setWebRootPath($path)
        {
            if ($tmpImage = Image::findOne(['path' => $path])) {
                $this->setAttributes($tmpImage->attributes, false);
                $this->isNewRecord = false;
            } else $this->path = $path;
        }

        public function getShortFileName($length = 18, $suffix = "...")
        {
            return substr($fn = $this->Name, 0, $length) . ((strlen($fn) > $length) ? $suffix : "");
        }

    */
    public function getRootPath()
    {
        return Module::getGallery()->RootPath . $this->path;
    }

    public function getWebRootPath()
    {
        return $this->path;
    }

    public function getName()
    {
        return preg_replace("/[^\/]*\//", "", $this->path);
    }

    public function getState()
    {
        if (!$this->path) return Image::STATE_EMPTY;
        if ($this->isNewRecord) return Image::STATE_UNSAVED;
        if (!file_exists($this->RootPath)) return Image::STATE_BROKEN;
        return Image::STATE_NORMAL;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->saveVersions();
            return true;
        } else {
            return false;
        }
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            $this->removeVersions();
            return true;
        } else {
            return false;
        }
    }

    public function saveVersions()
    {
        $imagine = YiiImage::getImagine();
        $newImage = $imagine->open($this->RootPath);

        foreach (Module::getGallery()->versions as $version => $func) {
            $fn = preg_replace("#(.*)(\.)([^\.]+)$#", "\\1$version.\\3", $this->RootPath);
            $func($newImage)->save($fn, ['jpeg_quality' => 100]);
            /*
                        if (file_exists($fn)) {
                        } else {
                            $func($newImage)->save($fn);
                        }
            */
        }
    }

    public function removeVersions($version = "ALL")
    {
        foreach (Module::getGallery()->versions as $k => $v) {
            if ($k == $version || $version == "ALL") {
                $fn = preg_replace("#(.*)(\.)([^\.]+)$#", "\\1-$k.\\3", $this->RootPath);
                if (file_exists($fn)) {
                    unlink($fn);
                }
            }
        }
        if (file_exists($this->RootPath)) {
            unlink($this->RootPath);
        }
    }

    public function getImageAuthors()
    {
        return $this->hasMany(ImageAuthor::className(), ['image_id' => 'id']);
    }

    public function getTranslations()
    {
        return $this->hasMany(ImageTranslation::className(), ['image_id' => 'id']);
    }
}