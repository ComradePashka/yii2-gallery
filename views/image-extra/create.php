<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model comradepashka\gallery\models\ImageExtra */

$this->title = 'Create Image Extra';
$this->params['breadcrumbs'][] = ['label' => 'Image Extras', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="image-extra-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'image_id' => $image_id
    ]) ?>

</div>
