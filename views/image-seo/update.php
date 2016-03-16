<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ImageSeo */

$this->title = 'Update Image Seo: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Image Seos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'image_id' => $model->image_id, 'lang' => $model->lang]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="image-seo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
