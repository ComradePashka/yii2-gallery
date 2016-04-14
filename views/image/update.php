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
use yii\helpers\Url;

$this->registerJs("
    $('#newPath' ).dialog({
        autoOpen: false,
        modal: true,
        height: 500,
        width: 800,
        open: function( event, ui ) {
            $.ajax({
                url: '" . Url::to(['default/ajax-file-list'])  ."',
                data: {cwd: $(this).attr('cwd')}
            })
            .done(function (data) {
                $('#albums').html('');
                $('#images').html('');
                $.each(data.albums, function(name, cwd) {
                    $('#albums').append('<a class=\'list-group-item album-item btnChangePath\' cwd=\'' + cwd + '\'>' + name + '</a>');
                });
                $.each(data.images, function(id, src) {
                    $('#images').append('<img class=\'imgChangePath\' src=\'' + src + '\' image-id=\'' + id + '\' width=64 heigth=64>');
                });
            })
            .error(function (jqXHR, status) {
                alert('Error!' + status);
            });
        },
        buttons: {
            '[+]': function() {
            }
        }
    });
    $('body').on('click', '.btnChangePath', function (e) {
        $('#newPath').dialog('close');
        $('#newPath').attr('cwd', $(this).attr('cwd'));
        $('#newPath').dialog('open');
    });
    $('body').on('click', '.imgChangePath', function (e) {
        if (confirm('Заменить на эту фотку? Сто пудов?')) {
//            location.href = '" . Url::to(['default/ajax-merge-image', 'currentPath' => Module::$currentPath, 'gallery' => Module::$galleryName]) . "';
            $.ajax({
                url: '" . Url::to(['default/ajax-merge-image'])  ."',
                data: {id: " . $model->id . ", newImageId: $(this).attr('image-id')}
            })
            .done(function (data) {
                console.log(data);
                location.href = '" . Url::to(['image/update', 'currentPath' => Module::$currentPath, 'gallery' => Module::$galleryName]) . "&id=' + data.id + '';
            })
            .error(function (jqXHR, status) {
                alert('Error!' + status);
            });
        }
        $('#newPath').dialog('close');
    });
");

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
    <div class="col-xs-2">
        <div class='thumbnail {$activeImage}'>
            <a href='<?= $model->path ?>' title='<?= $model->Name ?>' data-image-id='<?= $model->id ?>'><img src='<?= $model->path ?>' class='thumb' /></a>
            <div class='caption text-center toolbox'>
                <div><?= $model->Name ?></div>
            </div>
        </div>
    </div>
    <div class="col-xs-10">


        <?= $form->field($model, 'path', [
        'template' => '{label}<div class="col-sm-10"><div class="input-group">' .
                '{input}' .
                Html::tag("div",
                Html::button('<span class="glyphicon glyphicon-edit"></span>', ['class' => 'btn btn-default btnChangePath', 'cwd' => Module::$currentPath])
                , ['class' => 'input-group-btn']) .
                '</div></div><div class="col-sm-10">{error}</div>'
        ])->textInput(['maxlength' => true, 'disabled' => true])
        ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'header')->textInput(['maxlength' => true]) ?>
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