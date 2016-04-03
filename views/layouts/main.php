<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use comradepashka\gallery\Module;

if (Module::$imagePlugin != 'tinymce')
    $this->beginContent('@app/views/layouts/main.php');

//echo "<div class='row'> g: " . Module::$galleryName . "cp:" . Module::$currentPath . "</div>";

echo "<div class='panel panel-default'><div class='panel-heading'><b>" . Module::$galleryName . "</b>::". Module::$currentPath .
    " plugin: " . Module::$imagePlugin .
    " C/A: " . yii::$app->controller->id . "/" . yii::$app->controller->action->id . "</div><div class='panel-body'>";
if ((yii::$app->controller->id != "default") || (yii::$app->controller->id == "default" && yii::$app->controller->action->id != "index")) {
    echo "<div class='col-xs-2'><div class='list-group'>";
    echo Html::a("<span class='glyphicon glyphicon-home'></span>", ['/gallery'], ["class" => "list-group-item"]);
    foreach (Module::getAlbums() as $label => $path) {
        $link[0] = yii::$app->controller->action->id;
        $link['currentPath'] = $path;
        if (Module::$galleryName != "default") $link['galleryName'] = Module::$galleryName;
        echo Html::a("<span class='glyphicon glyphicon-folder-close'></span> $label ",
            $link, ["class" => "list-group-item"]
        );
    }
    echo "</div></div><div class='col-xs-10'>$content</div>";
} else {
    echo "<div class='row'>$content</div>";
}
echo "</div>";
if (Module::$imagePlugin != 'tinymce')
    $this->endContent();
?>