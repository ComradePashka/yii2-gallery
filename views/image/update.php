<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/15/2016
 * Time: 6:43 PM
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use comradepashka\gallery\Module;


    $versions = [];
    foreach (Module::getGallery()->Versions as $name => $func) {
        $url = preg_replace("/(\.[^$]+)$/", "$name\\1", $model->path);
        $versions[] = "<li><a href='{$url}' title='{$model->Name}' data-image-id='{$model->id}' data-image-ver='{$name}'>{$name}</a></li>";
    }
    $authorlist = "";
    foreach ($model->imageAuthors as $author) {
//        $authors[] = "<li>{$author->users->username}</li>";
        $authorlist .= "<li>{$author->users->username}</li>";
    }
    $extralist = "";
    foreach ($model->imageExtra as $extra) {
//        $extra[] = "<li>{$extra->category} :: {$extra->value}</li>";
        $extralist .= "<li>{$extra->category} :: {$extra->value}</li>";
    }


$form = ActiveForm::begin([
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
        'template' => "{label}<div class='col-sm-10'>{input}</div><div class='col-sm-10'>{error}</div>",
        'labelOptions' => ['class' => 'col-sm-1 control-label']
    ]
]);

?>
<div class="row">
    <div class="col-xs-3">
        <div class='thumbnail {$activeImage}'>
            <a href='<?= $model->path ?>' title='<?= $model->Name ?>' data-image-id='<?= $model->id ?>'><img src='<?= $model->path ?>' class='thumb' /></a>
            <div class='caption text-center toolbox'>
                <div><?= $model->Name ?></div>
            </div>
        </div>
    </div>
    <div class="col-xs-9">
        <div><?= $model->path ?></div>
        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'header')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>
        <div>Autors: <?= $authorlist ?></div>
        <div>Extra: <?= $extralist ?></div>
    </div>
</div>
<div class="form-group">
    <?= Html::a("<span class='glyphicon glyphicon-arrow-left'></span> Cancel", ['image/', 'currentPath' => Module::$currentPath], ['class' => 'btn btn-default']) ?>
    <?= Html::submitButton($model->isNewRecord ? Yii::t('i18n', 'Create') : Yii::t('i18n', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>
<?php
    ActiveForm::end();
?>