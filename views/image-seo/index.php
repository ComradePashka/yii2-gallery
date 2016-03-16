<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Image Seos';
$this->params['breadcrumbs'][] = $this->title;
echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'lang',
            'title',
            'header',
            'keywords',
            'description',
            [ 'class' => 'yii\grid\ActionColumn' ],
        ],
    ]);
// Yii::$app->request->isAjax
echo Html::a('+', ['create'], ['class' => 'btn btn-success']);
?>

