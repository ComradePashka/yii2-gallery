<?php

/* @var $this \yii\web\View */
/* @var $content string */

use comradepashka\gallery\models\Image;
use yii\helpers\Html;
use comradepashka\gallery\Module;
use yii\helpers\Url;

$this->registerJs("
    $('#createAlbum' ).dialog({
        autoOpen: false,
        width: 320,
        height: 280,
        modal: true,
        buttons: [{
            text: 'Добавить',
            icons: { primary: 'ui-icon-plusthick' },
            click : function() {
                $.ajax({
                    url: '" . Url::to(['default/ajax-create-album'])  ."',
                    data: {name: $(this).find('#name').val(), currentPath: '" . Module::$currentPath . "'}
                })
                .done(function (data) {
                    if (data.error) {
                        alert('Error!' + data.error);
                    } else location.href = location.pathname + '?currentPath=' + data.currentPath;
                })
                .error(function (jqXHR, status) {
                    alert('Error!' + status);
                });
            }
        }]
    });
    $('#btnCreateAlbum').on('click', function (e) {
        $('#createAlbum' ).dialog('open');
    });

    $('#ru_name').keyup(function (e) {
        $('#name').val(getSlug($(this).val()));
    });

        $('#extraDialog').dialog({
        width: 800,
        height: 600,
        autoOpen: false,
        open: function( event, ui ) {
            $.ajax({
                    url: $(this).data('url'),
/*
                    data: {image_id: $(this).find('#name').val(), currentPath: '" . Module::$currentPath . "'}
*/
                })
                .done(function (data) {
                    if (data.error) {
                        alert('Error!' + data.error);
                    } else {
                        console.log(data);
                        $(this).html(data);
                    }
                    return false;
                })
                .error(function (jqXHR, status) {
                    alert('Error!' + status);
                });
            return false;
        }
    });
    $('body').on('click', '.btnExtraDialog', function (e) {
        $('#extraDialog').dialog('close');
        $('#extraDialog').data('url', $(this).attr('href'));
        $('#extraDialog').dialog('open');
    });
");

if (Module::$imagePlugin != 'tinymce')
    $this->beginContent('@app/views/layouts/main.php');

echo "<div class='panel panel-default'><div class='panel-heading'><b>" . Module::$galleryName . "</b>::". Module::$currentPath .
    " plugin: " . Module::$imagePlugin .
    " C/A: " . yii::$app->controller->id . "/" . yii::$app->controller->action->id . "</div><div class='panel-body'>";
if ((yii::$app->controller->id != "default") || (yii::$app->controller->id == "default" && yii::$app->controller->action->id != "index")) {
    echo $this->render('_createAlbum');
    echo $this->render('_fileList');
    echo $this->render('_extraDialog');
    echo "<div class='col-xs-2'>" .
        Html::button("<span class='glyphicon glyphicon-plus'></span>", ["class" => "btn btn-default", 'id' => 'btnCreateAlbum']) .
        "<div class='list-group'>";
    echo Html::a("<span class='glyphicon glyphicon-home'></span>", ['/gallery'], ["class" => "list-group-item album-item"]);
    foreach (Module::getAlbums() as $label => $path) {
//        $link[0] = yii::$app->controller->action->id;
        $link[0] = yii::$app->controller->id . "/";
        $link['currentPath'] = $path;
        if (Module::$galleryName != "default") $link['galleryName'] = Module::$galleryName;
        echo Html::a("<span class='glyphicon glyphicon-folder-close'></span> $label",
            $link, ["class" => "list-group-item album-item"]
        );
    }
    echo "</div></div>";

    if (($image_id = Module::$image_id) && ($model = Image::findOne($image_id))) $content = "
    <div class='col-xs-2'>
        <div class='thumbnail'>
        {$model->getHtml('-25', ['class' => 'thumb', 'data-image-id' => $model->id])}
        <div class='caption text-center toolbox'>{$model->Name}</div>
        </div>
    </div>
    <div class='col-xs-10'>$content</div>";

    echo "<div class='col-xs-10'>$content</div>";
} else {
    echo "<div class='row'>$content</div>";
}
echo "</div>";
if (Module::$imagePlugin != 'tinymce')
    $this->endContent();
?>