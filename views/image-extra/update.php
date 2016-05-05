<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model comradepashka\gallery\models\ImageExtra */

$this->title = 'Update Image Extra: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Image Extras', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="image-extra-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'image_id' => $image_id
    ]) ?>

</div>
