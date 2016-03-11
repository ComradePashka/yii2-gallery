<?php
namespace comradepashka\gallery;

class Module extends \yii\base\Module
{
    public $test;
    public $test2 = "test2";
    public $test3 = "test3";

    public $controllerNamespace = 'comradepashka\gallery\controllers';

    public function init()
    {
        parent::init();

        $this->params['foo'] = 'bar';
        // ...  other initialization code ...
    }
}

?>