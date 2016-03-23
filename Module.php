<?php
namespace comradepashka\gallery;

use comradepashka\gallery\models\Gallery;
use comradepashka\gallery\models\Image;
use Yii;
use yii\base\BootstrapInterface;
use yii\web\View;
use Imagine\Image\Box;
use yii\imagine\Image as YiiImage;

class Module extends \yii\base\Module implements BootstrapInterface
{

    public $galleries = [];
    /**
     * @var Gallery[]
     */
    private static $_galleries;
    /**
     * @var string
     */
    public static $galleryName;
    /**
     * @var string
     */
    public static $currentPath;

    public $controllerNamespace = 'comradepashka\gallery\controllers';

    /**
     * @return Gallery
     */
    public static function getGallery()
    {
        if (!isset(self::$_galleries[self::$galleryName])) self::$galleryName = 'default';
        return self::$_galleries[self::$galleryName];
    }

    public static function getParentPath($path)
    {
        return preg_replace("/[^\/]+\/$/", "", $path);
    }

    public function getFileName($path)
    {
        return preg_replace("/[^\/]*\//", "", $path);
    }

    public static function getAlbums()
    {
        $albums = [];
        if (self::$currentPath != "/") $albums['[..]'] = self::getParentPath(self::$currentPath);
        $path = self::getGallery()->getWebRootPath() . "/" . self::$currentPath;
        $h = opendir($path);
        while (false !== ($entry = readdir($h))) {
            if ($entry != "." && $entry != "..") {
                if (is_dir($path . $entry)) {
                    $albums[$entry] = self::$currentPath . $entry . "/";
                }
            }
        }
        closedir($h);
        return $albums;
    }

    /**
     * $return Images
     */
    public static function getImages()
    {
//        SELECT * FROM `image` WHERE `path` RLIKE '^/images/[^/]+$'
        return Image::find()->where(['rlike', 'path', "^" . self::getGallery()->getWebPath() . self::$currentPath . "[^/]+$"])->all();
    }

    public function bootstrap($app)
    {
// is private static configurable? have to check it
        self::$_galleries = array_merge($this->galleries,
            ['default' => [
                'class' => 'comradepashka\gallery\models\Gallery',
                'rootPath' => '@frontend/web',
                'webPath' => '/images',
                'placeholder' => '/placeholder.png',
                'extensions' => '/(jpg|png|gif)$/i',
                'versions' => [
                    "small" =>
                        function ($img) {
                            return $img->thumbnail(new Box(128, 128));
                        },
                    "medium" =>
                        function ($img) {
                            return $img->thumbnail(new Box(400, 400));
                        }
                ]
            ]]);
        $this->galleries = self::$_galleries;
        foreach (self::$_galleries as $id => $config) {
            $config['name'] = $id;
            $config['module'] = $this;
            self::$_galleries[$id] = Yii::createObject($config);
        }
        if (!$this->layout) $this->layout = 'main';
//        YiiImage::$driver = YiiImage::DRIVER_GD2;
        yii::trace("Imagine driver:" . json_encode(YiiImage::$driver));
    }

    public static function checkConfig($post)
    {
        foreach ($post as $key => $val) {
            switch ($key) {
                case "currentPath":
                    self::$currentPath = $val;
                    break;
                case "galleryName":
                    self::$galleryName = $val;
                    break;
                /*
                 * just a sample
                                case "imagePlugin" :
                                    if (preg_match("(seo|extra|authors)", $val)) {
                                        self::$imagePlugin = $val;
                                    }
                                    break;
                */
            }
        }
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        self::$galleryName = 'default';
        self::$currentPath = '/';
        self::checkConfig(yii::$app->request->queryParams);
        Yii::$app->getView()->on(View::EVENT_END_BODY, [$this, 'registerGalleryAsset']);
        return true;
    }

    public function registerGalleryAsset($event)
    {
        /**
         * @var View $view
         */
        if (Yii::$app->getRequest()->getIsAjax()) {
            return;
        }
        $view = $event->sender;
        GalleryAsset::register($view);
    }
}

/*

    public function create($name)
    {
        if (@mkdir($this->WebRootPath . $name)) return new Album(['path' => $this->path . $name . "/"]);
        else {
            $this->addError("path", "Can not create album: " . $this->WebRootPath . $name);
            return $this;
        }
    }

    public function update($newname)
    {
        if (@rename($this->WebRootPath, dirname($this->WebRootPath) . "/" . $newname)) {
            $this->path = $this->ParentPath . $newname . "/";
        } else {
            $this->addError("path", "Can not rename album: $this->Name");
        }
        return $this;
    }

    public function delete()
    {
        if (!@rmdir($this->WebRootPath)) {
            $this->addError("path", "Can not delete album: $this->Name");
        }
        $this->path = $this->ParentPath;
        return $this;
    }

    public function find($path)
    {
        if ($this->path == $path) return $this;
        foreach ($this->albums as $a) {
            if ($album = $a->find($path)) return $album;
        }
        return null;
    }



 */


?>