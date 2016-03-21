<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Modal;
use comradepashka\gallery\Module;

$this->beginContent('@app/views/layouts/main.php');
//AppAsset::register($this);
/*
Modal::begin(['id' => 'popup-modal']);
echo Html::tag("div", 'test', ['id' => 'popup-modal-content']);
Modal::end();
*/
//echo "<div class='row'> g: " . Module::$galleryName . "cp:" . Module::$currentPath . "</div>";

echo "<div class='col-xs-12'>" . Module::$currentPath . "</div>";
foreach (Module::getAlbums() as $label => $path) {
    echo Html::a("<span class='glyphicon glyphicon-folder-close'></span> $label",
        ['view', 'currentPath' => $path], ["class" => "btn btn-sm btn-default"]);
}
echo "<div class='row'>$content</div>";

$this->endContent();
?>