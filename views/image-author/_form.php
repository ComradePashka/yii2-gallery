<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ImageAuthor */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="image-author-form">

    <?php
        $users = ArrayHelper::map(\common\models\User::find()->asArray()->all(), 'id', 'username');
        $form = ActiveForm::begin();
        $model->image_id = $image_id;
        echo $form->field($model, 'image_id')->hiddenInput()->label(false);
        echo $form->field($model, 'user_id')->dropDownList($users);
        echo $form->field($model, 'notes')->textarea();
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('i18n', 'Create') : Yii::t('i18n', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
