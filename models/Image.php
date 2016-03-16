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

    private $_gallery;

//    private $_webRootPath;

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

    public function getGallery()
    {
        if (!$this->_gallery) {
            $this->_gallery = Module::getInstance()->galleries['default'];
        }
        return $this->_gallery;
    }

    public function setGallery($gallery)
    {
        $this->_gallery = $gallery;
    }

    public function getWebRootPath()
    {
        return $this->path;
    }

    public function setWebRootPath($path)
    {
        if ($tmpImage = Image::findOne(['path' => $path])) {
            $this->setAttributes($tmpImage->attributes, false);
            $this->isNewRecord = false;
        } else $this->path = $path;
    }

    public function getRootPath()
    {
        return Module::$gallery->RootPath . $this->path;
    }

    public function getName()
    {
        return preg_replace("/[^\/]*\//", "", $this->path);
    }

    public function getParentPath()
    {
        return str_replace(Module::$gallery->WebRoot, "", preg_replace("/[^\/]*$/", "", $this->path));
    }

    public function getShortFileName($length = 18, $suffix = "...")
    {
        return substr($fn = $this->Name, 0, $length) . ((strlen($fn) > $length) ? $suffix : "");
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
            yii::trace('saving: ' . $this->path, 'SAV');
            $this->saveVersions();
            return true;
        } else {
            return false;
        }
    }
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            yii::trace('deleting: ' . $this->path, 'DEL');
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

        foreach (Module::$gallery->versions as $k => $v) {
            $fn = preg_replace("#(.*)(\.)([^\.]+)$#", "\\1-$k.\\3", $this->RootPath);
            if (file_exists($fn)) {
            } else {
                $v($newImage)->save($fn);
            }
        }
    }
    public function removeVersions($version = "ALL")
    {
        foreach (Module::$gallery->versions as $k => $v) {
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
/*

    $images = Image::find()->where(['rlike', 'path', "^{$path}[^/]+$"])->all();

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