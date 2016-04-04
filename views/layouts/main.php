<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use comradepashka\gallery\Module;
use yii\helpers\Url;

$this->registerJs("
    $('#createAlbum' ).dialog({
        autoOpen: false,
        width: 320,
        height: 280,
        modal: true,
        buttons: {
            '[+]': function() {
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
        }
    });
    $('#btnCreateAlbum').on('click', function (e) {
        $('#createAlbum' ).dialog('open');
    });

    $('#ru_name').keyup(function (e) {
        $('#name').val(getSlug($(this).val()));
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
    echo "<div class='col-xs-2'>" .
        Html::button("<span class='glyphicon glyphicon-plus'></span>", ["class" => "btn btn-default", 'id' => 'btnCreateAlbum']) .
        "<div class='list-group'>";
    echo Html::a("<span class='glyphicon glyphicon-home'></span>", ['/gallery'], ["class" => "list-group-item"]);
    foreach (Module::getAlbums() as $label => $path) {
//        $link[0] = yii::$app->controller->action->id;
        $link[0] = yii::$app->controller->id . "/";
        $link['currentPath'] = $path;
        if (Module::$galleryName != "default") $link['galleryName'] = Module::$galleryName;
        echo Html::a("<span class='glyphicon glyphicon-folder-close'></span> $label",
            $link, ["class" => "list-group-item"]
        );
    }
    echo "</div></div><div class='col-xs-10'>$content</div>";
} else {
    echo "<div class='row'>$content</div>";
}
echo "</div>";
if (Module::$imagePlugin != 'tinymce')
    $this->endContent();
?>