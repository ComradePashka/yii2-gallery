<?php
namespace comradepashka\gallery;

use yii\base\BootstrapInterface;
use yii\imagine\Image;

class Module extends \yii\base\Module implements BootstrapInterface
{
    public $test;

    public $test2;

    public $galleries = [];

    public $controllerNamespace = 'comradepashka\gallery\controllers';

    public function bootstrap($app)
    {
        if (!$this->galleries)
            $this->galleries['default'] = [
                'root' => '@frontend/web/images',
                'webRoot' => '/images',
                'placeholder' => '/placeholder.jpg',
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
    }

    public function init()
    {
        parent::init();
//        $this->params['foo'] = 'bar';
    }
}

?>