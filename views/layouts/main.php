<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Modal;
use comradepashka\gallery\Module;

$this->beginContent('@app/views/layouts/main.php');

//echo "<div class='row'> g: " . Module::$galleryName . "cp:" . Module::$currentPath . "</div>";

echo "<div class='col-xs-12'>" . Module::$currentPath . "</div>";
foreach (Module::getAlbums() as $label => $path) {
    $link[0] = 'view';
    $link['currentPath'] = $path;
    if (Module::$galleryName != "default") $link['galleryName'] = Module::$galleryName;
    echo Html::a("<span class='glyphicon glyphicon-folder-close'></span> $label",
        $link, ["class" => "btn btn-sm btn-default"]);
}
echo "<div class='row'>$content</div>";

$this->endContent();
?>