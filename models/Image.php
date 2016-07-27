<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/12/2016
 * Time: 7:04 PM
 */

namespace comradepashka\gallery\models;

use Yii;
use yii\bootstrap\Html;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\imagine\Image as YiiImage;
use creocoder\translateable\TranslateableBehavior;
use creocoder\taggable\TaggableBehavior;
use comradepashka\gallery\Module;
use comradepashka\seokit\UrlHistoryBehavior;

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

    const ORIENTATION_HORIZONTAL = 1;
    const ORIENTATION_VERTIACAL = 2;
    const ORIENTATION_SQUARE = 3;

    public static $reGenPicture = true;

    public static function tableName()
    {
        return 'image';
    }

    public function rules()
    {
        return [
            [['path'], 'required'],
            [['path', 'title', 'description', 'header', 'keywords'], 'string', 'max' => 255],
            ['tagValues', 'safe'],
        ];
    }

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'translateable' => [
                'class' => TranslateableBehavior::className(),
                'translationAttributes' => ['title', 'description', 'keywords', 'header'],
            ],
            'taggable' => [
                'class' => TaggableBehavior::className(),
            ],
            [
                'class' => TimestampBehavior::className(),
                'value' => function () { return date('U'); }
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

    public function getOrientation()
    {
        $imagine = YiiImage::getImagine();
        $newImage = $imagine->open($this->RootPath);
        $w = $newImage->getSize()->getWidth();
        $h = $newImage->getSize()->getHeight();
        if ($w == $h) return self::ORIENTATION_SQUARE;
        if ($w > $h) return self::ORIENTATION_HORIZONTAL;
        else return self::ORIENTATION_VERTIACAL;
    }

    public function getProgress()
    {
        $progress = 0;
        if ($this->title) $progress += 25;
        if ($this->description) $progress += 25;
        if ($this->keywords) $progress += 25;
        if ($this->header) $progress += 15;
        if ($this->imageAuthors) $progress += 5;
        if ($this->imageExtra) $progress += 5;
        return $progress;
    }

    public function getHtml($version, $opt=[])
    {
        $opt['data-image-id'] = $this->id;
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
            $func($newImage->copy())->save($fn, ['jpeg_quality' => 100]);
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
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])->viaTable('{{%image_tag_assn}}', ['image_id' => 'id']);
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

    public static function find()
    {
        return new ImageQuery(get_called_class());
    }
}