<?php

namespace comradepashka\gallery;

use Yii;
//use comradepashka\gallery\Module as gModule;

/**
 * This is just an example.
 */
class AutoloadExample extends \yii\base\Widget
{
    public function run()
    {
        $module = Module::getInstance();
//        $module = Yii::$app->controller->module;
        $module = Yii::$app->getModule('gallery');

        if ($module) return print_r($module->id) . "Hello! test: $module->test2";
        else return "Module is null!!!";
    }
}
