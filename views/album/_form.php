<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/13/2016
 * Time: 3:07 PM
 */

use yii\bootstrap\Html;

if ($name) echo "Edit album for gallery: <b>$gallery</b> into: $currentPath";
else echo "Add album for gallery: <b>$gallery</b> into: $currentPath";

echo Html::beginForm();
echo Html::textInput('name', $name);
echo Html::submitButton('Save!', ['class' => 'showModalButton']);
echo Html::endForm();
