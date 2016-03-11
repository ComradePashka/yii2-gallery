<?php

namespace comradepashka\gallery;

use Yii;

/**
 * This is just an example.
 */
class AutoloadExample extends \yii\base\Widget
{
    public function run()
    {
//        $module = Module::getInstance();
        $module = Yii::$app->module;
        if ($module) return "Hello! test: $module->test test2: $module->test2 test3: $module->test3 ";
        else return "Module is null!!!";
    }
}
