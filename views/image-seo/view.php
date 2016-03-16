<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ImageSeo */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Image Seos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="image-seo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'image_id' => $model->image_id, 'lang' => $model->lang], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'image_id' => $model->image_id, 'lang' => $model->lang], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'image_id',
            'lang',
            'title',
            'header',
            'keywords',
            'description',
        ],
    ]) ?>

</div>
