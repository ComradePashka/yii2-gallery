<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ImageSeo */

$this->title = 'Create Image Seo';
$this->params['breadcrumbs'][] = ['label' => 'Image Seos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="image-seo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
