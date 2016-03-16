<?php
namespace comradepashka\gallery;

use Yii;
use yii\base\BootstrapInterface;
use yii\web\Application;
use yii\web\View;

class Module extends \yii\base\Module implements BootstrapInterface
{
    public $galleries = [];
    public $controllerNamespace = 'comradepashka\gallery\controllers';

    public function bootstrap($app)
    {
        if (!$this->galleries)
            $this->galleries['default'] = [
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
        $app->on(Application::EVENT_BEFORE_REQUEST, function () use ($app) {
            $app->getView()->on(View::EVENT_END_BODY, [$this, 'registerToolsAsset']);
        });
    }
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        foreach ($this->galleries as $id => $config) {
            $config['name'] = $id;
            $config['module'] = $this;
            $this->galleries[$id] = Yii::createObject($config);
            return true;
        }
    }
    public function registerToolsAsset($event)
    {
        $view = $event->sender;
        ToolsAsset::register($view);
    }
    public function init()
    {
        parent::init();
//        $this->params['foo'] = 'bar';
    }
}

?>