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
echo "<div class='row'>cp:" . Module::$currentPath . " a:" . Module::$currentAlbum->path . " id: " . Module::$currentImage->id . " plugin:" . Module::$imagePlugin . "</div>";
echo "<div class='row'>$content</div>";

$this->endContent();
?>