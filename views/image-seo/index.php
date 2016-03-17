<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$extClass = "";
if (yii::$app->request->isAjax) {
    $extClass = "showModalButton";
/*
foreach ($image->imageSEO as $seo) { echo json_encode($seo->attributes) . "<br />"; }
*/
}

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
            [
                'class' => 'yii\grid\ActionColumn',
                'buttonOptions' => ['class' => $extClass],
            ],
        ],
    ]);
    echo Html::a('+', ['create', 'image_id' => ], ['class' => 'btn btn-success' . (($extClass) ? " " . $extClass : "")]);
?>
