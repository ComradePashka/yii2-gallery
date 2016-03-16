<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ImageAuthor */

$this->title = Yii::t('i18n', 'Update {modelClass}: ', [
    'modelClass' => 'Image Author',
]) . ' ' . $model->image_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('i18n', 'Image Authors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->image_id, 'url' => ['view', 'image_id' => $model->image_id, 'user_id' => $model->user_id]];
$this->params['breadcrumbs'][] = Yii::t('i18n', 'Update');
?>
<div class="image-author-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
