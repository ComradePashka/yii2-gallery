<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use comradepashka\gallery\Module;

if (Module::$imagePlugin != 'tinymce')
    $this->beginContent('@app/views/layouts/main.php');

//echo "<div class='row'> g: " . Module::$galleryName . "cp:" . Module::$currentPath . "</div>";

echo "<div class='col-xs-12'>" . Module::$currentPath . " CTL: " . yii::$app->controller->id . " ACT: " . yii::$app->controller->action->id . "</div>";
if ((yii::$app->controller->id != "default") || (yii::$app->controller->id == "default" && yii::$app->controller->action->id != "index")) {
    echo Html::a("<span class='glyphicon glyphicon-home'></span>", ['/gallery'], ["class" => "btn btn-sm btn-default"]) . "&nbsp;";
    foreach (Module::getAlbums() as $label => $path) {
        $link[0] = yii::$app->controller->action->id;
        $link['currentPath'] = $path;
        if (Module::$galleryName != "default") $link['galleryName'] = Module::$galleryName;
        echo Html::a("<span class='glyphicon glyphicon-folder-close'></span> $label",
            $link, ["class" => "btn btn-sm btn-default"]);
    }
}
echo "<div class='row'>$content</div>";
if (Module::$imagePlugin != 'tinymce')
    $this->endContent();
?>