<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\LanguageDropdown;

/* @var $this yii\web\View */
/* @var $model common\models\ImageSeo */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="image-seo-form">

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}<div class='col-sm-10'>{input}</div><div class='col-sm-10'>{error}</div>",
            'labelOptions' => ['class' => 'col-sm-1 control-label']
        ]
    ]); ?>

    <?= $form->field($model, 'image_id')->textInput() ?>
    <?= $form->field($model, 'lang')->dropDownList(LanguageDropdown::getLanguageList()) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'header')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
