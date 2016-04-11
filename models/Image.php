<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/12/2016
 * Time: 7:04 PM
 */

namespace comradepashka\gallery\models;

use comradepashka\seokit\UrlHistoryBehavior;
use Yii;
use yii\bootstrap\Html;
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

    public static $reGenPicture = true;

    public static function tableName()
    {
        return 'image';
    }

    public function rules()
    {
        return [
            [['path'], 'required'],
            [['path', 'title', 'description', 'header', 'keywords'], 'string', 'max' => 255]
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
            ],
            [
                'class' => UrlHistoryBehavior::className(),
                'url_attribute' => 'path'
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

    public function getWebVersionPath($version)
    {
        return preg_replace("/(\.[^$]+)$/", "$version\\1", $this->path);
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

    public function getProgress()
    {
        $progress = 0;
        if ($this->title) $progress += 20;
        if ($this->description) $progress += 20;
        if ($this->keywords) $progress += 20;
        if ($this->header) $progress += 10;
        if ($this->imageAuthors) $progress += 10;
        if ($this->imageExtra) $progress += 10;
        return $progress;
    }

    public function getHtml($version, $opt=[])
    {
        return Html::img($this->getWebVersionPath($version), array_merge(['alt' => $this->title, 'title' => $this->description], $opt));
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (Image::$reGenPicture) $this->saveVersions();
            return true;
        } else {
            return false;
        }
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            $this->removeVersions();
            if (file_exists($this->RootPath)) {
                unlink($this->RootPath);
            }
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
        }
    }

    public function removeVersions($version = "ALL")
    {
        foreach (Module::getGallery()->versions as $k => $v) {
            if ($k == $version || $version == "ALL") {
                $fn = preg_replace("#(.*)(\.)([^\.]+)$#", "\\1$k.\\3", $this->RootPath);
                if (file_exists($fn)) {
                    unlink($fn);
                }
            }
        }
    }

    public function getImageAuthors()
    {
        return $this->hasMany(ImageAuthor::className(), ['image_id' => 'id']);
    }

    public function getImageExtra()
    {
        return $this->hasMany(ImageExtra::className(), ['image_id' => 'id']);
    }

    public function getTranslations()
    {
        return $this->hasMany(ImageTranslation::className(), ['image_id' => 'id']);
    }
}