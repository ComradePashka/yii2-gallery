<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ImageAuthor */

$this->title = Yii::t('i18n', 'Create Image Author');
$this->params['breadcrumbs'][] = ['label' => Yii::t('i18n', 'Image Authors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="image-author-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
