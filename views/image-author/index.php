<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('i18n', 'Image Authors');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="image-author-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('i18n', 'Create Image Author'), ['create', 'image_id' => $image_id], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
        foreach ($models as $model) {
            echo Html::tag('div',
                Html::tag('span', "{$model->user->profile->name} (<b>{$model->user->username}</b>) [ <i>{$model->notes}</i> ]", ['class' => 'input-group-addon']) .
                Html::tag('div',
                    Html::a('<span class="glyphicon glyphicon-edit"></span>', ['update', 'image_id' => $image_id, 'user_id' => $model->user_id], ['class' => 'btn btn-success']) .
                    Html::a('<span class="glyphicon glyphicon-remove"></span>', ['delete', 'image_id' => $image_id, 'user_id' => $model->user_id], ['class' => 'btn btn-danger'])
                , ['class' => 'input-group-btn']),
                ['class' => 'btn-group btn-group-xs']
            );
            //<a href='update?image_id=$image_id&user_id={$model->user_id}'>edit</a>
            //echo "<div class='row'>{$model->user->username} :: {$model->notes}</div>";
        }
    ?>

</div>
