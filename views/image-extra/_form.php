<?php

use comradepashka\gallery\models\ImageExtra;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model comradepashka\gallery\models\ImageExtra */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="image-extra-form">

    <?php $form = ActiveForm::begin();
        $model->image_id = $image_id;
        echo $form->field($model, 'image_id')->hiddenInput();
/*
        $categories= ImageExtra::find()
        ->select(['category as value', 'category as label'])
        ->distinct()
        ->asArray()
        ->all();
*/
    ?>
    <?= $form->field($model, 'category')->widget(
        AutoComplete::className(), [
/*
        'clientOptions' => [
            'source' => $categories,
        ],
*/
        'clientOptions' => [
            'source'=>new JsExpression("function(request, response) {
                $.getJSON('" . Url::to(['default/ajax-image-extra-autocomplete']) . "', {
                item: 'category',
                term: request.term
                }, response);
            }")
        ],
        'options'=>[
            'class'=>'form-control'
        ]
    ]);
    ?>
    <?= $form->field($model, 'value')->widget(
        AutoComplete::className(), [
        'clientOptions' => [
            'source'=>new JsExpression("function(request, response) {
                $.getJSON('" . Url::to(['default/ajax-image-extra-autocomplete']) . "', {
                item: 'value',
                term: request.term
                }, response);
            }")
        ],
        'options'=>[
            'class'=>'form-control'
        ]
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
