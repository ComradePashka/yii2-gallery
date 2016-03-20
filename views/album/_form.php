<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/13/2016
 * Time: 3:07 PM
 */

use yii\bootstrap\Html;
use comradepashka\gallery\Module;

if ($name) echo "Edit album for gallery: <b>" . Module::$gallery->name . "</b> into: " . Module::$currentPath;
else echo "Add album for gallery: <b>" . Module::$gallery->name . "</b> into: " . Module::$currentPath;

echo Html::beginForm();
echo Html::textInput('name', $name);
echo Html::submitButton('Save!');
echo Html::endForm();
