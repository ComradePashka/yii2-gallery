<?php
namespace comradepashka\gallery;

use comradepashka\gallery\models\Album;
use comradepashka\gallery\models\Gallery;
use comradepashka\gallery\models\Image;
use Yii;
use yii\base\BootstrapInterface;
use yii\web\Application;
use yii\web\View;
use Imagine\Image\Box;

class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * @var Gallery[]
     */
    public static $galleries = [];
    /**
     * @var Gallery
     */
    public static $gallery;
    /**
     * @var Album
     */
    public static $rootAlbum;
    /**
     * @var Album
     */
    public static $currentAlbum;
    /**
     * @var string
     */
    public static $currentPath;

    /**
     * @var Image
     */
    public static $currentImage;

    /**
     * @var string
     */
    public static $imagePlugin;

    /**
     * @var string
     */
    public static $lastError;

    public $controllerNamespace = 'comradepashka\gallery\controllers';

    /**
     * @return Gallery
     */
    public static function getGallery()
    {
        /*
                if (!self::$gallery) {
                    self::$gallery = new Gallery();
                }
        */
        return self::$gallery;
    }

    /**
     * @param Gallery $gallery
     */
    public static function setGallery($gallery)
    {
        self::$gallery = $gallery;
    }

    /**
     * @return Album
     */
    public static function getRootAlbum()
    {
        return self::$rootAlbum;
    }

    /**
     * @param Album $rootAlbum
     */
    public static function setRootAlbum($rootAlbum)
    {
        self::$rootAlbum = $rootAlbum;
    }

    /**
     * @return Image
     */
    public static function getCurrentImage()
    {
        return self::$currentImage;
    }

    /**
     * @param Image $currentImage
     */
    public static function setCurrentImage($currentImage)
    {
        self::$currentImage = $currentImage;
    }

    /**
     * @param string $imagePlugin
     */
    public static function setImagePlugin($imagePlugin)
    {
        self::$imagePlugin = $imagePlugin;
    }

    /**
     * @return string
     */
    public static function getImagePlugin()
    {
        return self::$imagePlugin;
    }

    /**
     * @return models\Gallery[]
     */
    public static function getGalleries($galleryName)
    {
        if (isset(self::$galleries[$galleryName])) return self::$galleries[$galleryName];
        return self::$galleries['default'];
    }

    /**
     * @param models\Gallery[] $galleries
     */
    public static function setGalleries($gallaryName, $gallery)
    {
        self::$galleries[$gallaryName] = $gallery;
    }

    /**
     * @return Album
     */
    public static function getCurrentAlbum()
    {
        return self::$currentAlbum;
    }

    /**
     * @param Album $currentAlbum
     */
    public static function setCurrentAlbum($currentAlbum)
    {
        self::$currentAlbum = $currentAlbum;
    }

    public function bootstrap($app)
    {
        if (!self::$galleries)
            self::$galleries['default'] = [
                'class' => 'comradepashka\gallery\models\Gallery',
                'rootPath' => '@frontend/web',
                'webRoot' => '/images',
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
            ];
        if (!$this->layout) $this->layout = 'main';
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
        yii::trace("GALERY MODULE id: " . $this->id);
        yii::trace("GALERY MODULE handle END_BODY from: {$view->viewFile}");
        GalleryAsset::register($view);
    }

    public static function checkConfig($post)
    {
        foreach ($post as $key => $val) {
            switch ($key) {
                case "currentPath":
                    self::$currentPath = $val;
                    break;
                case "gallery":
                    self::$gallery = self::getGalleries($val);
                    break;
                case "image_id":
                    if ($image = Image::findOne($val)) {
                        self::$currentImage = $image;
                    };
                    break;
                case "imagePlugin" :
                    if (preg_match("(seo|extra|authors)", $val)) {
                        self::$imagePlugin = $val;
                    }
                    break;
            }
        }
    }

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        foreach (self::$galleries as $id => $config) {
            $config['name'] = $id;
            $config['module'] = $this;
            self::$galleries[$id] = Yii::createObject($config);
        }
        self::$gallery = self::$galleries['default'];
        self::$rootAlbum = new Album();
        self::$rootAlbum->path = '/';
        self::$currentAlbum = new Album();
        self::$currentPath = '/';
        self::$currentImage = new Image();
        self::checkConfig(yii::$app->request->queryParams);
        self::$currentAlbum->path = self::$currentPath;
        Yii::$app->getView()->on(View::EVENT_END_BODY, [$this, 'registerGalleryAsset']);

        return true;
    }

    public function init()
    {
        parent::init();
//        $this->params['foo'] = 'bar';
    }
}

?>