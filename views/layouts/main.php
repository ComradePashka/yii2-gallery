<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Modal;
use frontend\assets\AppAsset;

$this->beginContent('@app/views/layouts/main.php');
AppAsset::register($this);

Modal::begin(['id' => 'popup-modal']);
echo Html::tag("div", 'test', ['id' => 'popup-modal-content']);
Modal::end();

echo "<div class='row'>YO!</div>";
echo $content;

$this->endContent();
?>