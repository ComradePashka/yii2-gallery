<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Image Extras';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="image-extra-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Image Extra', ['create', 'image_id' => $image_id], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'category',
            'value',
            [
                'class' => 'yii\grid\ActionColumn',

                'buttons' => [
/*
                    'update' => function ($url, $model) use ($image_id) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'image_id' => $image_id]);
                    },
*/
                    'delete' => function ($url, $model) use ($image_id) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, ['data-method' => 'post']);
                    },
                ],

                'template' => '{update} {delete}',
                'urlCreator' => function ( $action, $model, $key, $index ) {
                    return Url::to([$action, 'id' => $model->id, 'image_id' => $model->image_id]);
                }
            ],
        ],
    ]); ?>
</div>
