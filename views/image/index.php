<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/15/2016
 * Time: 6:43 PM
 */

use comradepashka\gallery\models\ImageSeo;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\icons\Icon;
use devgroup\dropzone\DropZone;
use comradepashka\gallery\Module;

$this->registerJs("
    $('#btnDelAll').on('click', function (e) {
        if (confirm('УДАЛИТЬ ВСЁ???!!!!!')) {
            location.href = '" . Url::to(['default/delete-all', 'currentPath' => Module::$currentPath, 'gallery' => Module::$galleryName]) . "';
        }
    });
    $('.btnDeleteImage').on('click', function (e) {
        if (confirm('УДАЛИТЬ???')) {
            location.href = '" . Url::to(['image/delete', 'currentPath' => Module::$currentPath, 'gallery' => Module::$galleryName]) . "&id=' + $(this).data('image-id');
        }
    });
");

$n = 0;
$images = Module::getImages();
$row = Html::tag("div",
        Html::button('<span class="glyphicon glyphicon-remove"></span>DELETE ALL!!!',['id' => 'btnDelAll', 'class' => 'btn btn-danger']),
    ['class' => 'col-xs-12' . ((count($images) == 0) ? " hidden" : "" )]);

foreach ($images as $i) {
    $allBtnClass = "btn btn-sm";
    $btnImageThumbs = $btnCloneMeta = $btnCloneExtra = $btnCloneAuthor = $btnCloneTags= $btnImageDelete = $allBtnClass;
    $btnImageThumbs .= " btn-info";
    $btnImageExtra = $btnImageAuthors = "$allBtnClass btnExtraDialog";

    if ($i->imageExtra) {
        $btnImageExtra .= " btn-info";
        $btnCloneExtra .= " btn-success";
    } else $btnImageExtra .= " btn-warning";
    if ($i->imageAuthors) {
        $btnImageAuthors .= " btn-info";
        $btnCloneAuthor .= " btn-success";
    } else $btnImageAuthors .= " btn-warning";
    if ($i->tags) {
        $btnCloneTags .= " btn-success";
    } else $btnCloneTags .= " btn-warning";

    $btnCloneMetaEx = 0;
    if ($i->Progress >= 20) $btnCloneMetaEx = " btn-info";
    if ($i->Progress >= 40) $btnCloneMetaEx = " btn-warning";
    if ($i->Progress >= 80) $btnCloneMetaEx = " btn-success";
    $btnCloneMeta .= $btnCloneMetaEx;
    $btnImageDelete .= " btn-danger";

//    Html::a('<span class="glyphicon glyphicon-share"></span>', ['default/clone-meta', 'id' => $i->id, 'currentPath' => Module::$currentPath], ['id' => 'btnCloneMeta', 'class' => 'btn btn-sm btn-default']) .
//    Html::button('<span class="glyphicon glyphicon-remove"></span>', ['class' => 'btn btn-sm btn-danger btnDeleteImage', 'image-id' => $i->id]) .

    /*
        $activeImage = "thumb-image";
        if ((Module::$currentImage->id) && (Module::$currentImage->id == $i->id)) {
            $activeImage .= " thumb-image-active";
        }
*/
    $versions = [];
    foreach (Module::getGallery()->Versions as $name => $func) {
        $url = $i->getWebVersionPath($name);
        $versions[] = "<li><a href='{$url}' data-origin='{$i->WebRootPath}' data-image-id='{$i->id}' data-image-version='{$name}'>{$name}</a></li>";
    }

    $row .= "
<div class='col-xs-2'>
<div class='thumbnail'>" .
Html::a($i->getHtml("-tiny", ['class' => 'thumb' ,'title' => $i->Name]),
    ['image/update', 'id' => $i->id, 'currentPath' => Module::$currentPath, 'gallery' => Module::$galleryName]) .
"<div class='caption text-center'>{$i->Name}
<div class='progress zero-margin'>
  <div class='progress-bar' role='progressbar' aria-valuenow='{$i->Progress}' aria-valuemin='0' aria-valuemax='100' style='min-width: 2em; width: {$i->Progress}%;'>
        {$i->Progress}%
  </div>
</div>";

    if (Module::$imagePlugin == "tinymce") {
        $row .= "<div class='text-center'>" .
            ButtonDropdown::widget([
                'label' => 'Version',
                'options' => ['class' => 'btn-xs' ],
                'dropdown' => ['items' => $versions ],
            ]) . "</div>";
    }

    $row .= Html::tag('div',
        Html::a(Icon::show('share', [], Icon::WHHG), ['default/clone-meta', 'id' => $i->id, 'currentPath' => Module::$currentPath], ['class' => $btnCloneMeta]) .
        Html::a(Icon::show('notificationbottom', [], Icon::WHHG), ['default/clone-extra', 'id' => $i->id, 'currentPath' => Module::$currentPath], ['class' => $btnCloneExtra]) .
        Html::a(Icon::show('user', [], Icon::WHHG), ['default/clone-author', 'id' => $i->id, 'currentPath' => Module::$currentPath], ['class' => $btnCloneAuthor]) .
        Html::a(Icon::show('tags', [], Icon::WHHG), ['default/clone-tags', 'id' => $i->id, 'currentPath' => Module::$currentPath], ['class' => $btnCloneTags]),
        ['class' => 'btn-group btn-group-xs pull-left']
    );
    $row .= Html::tag('div',
        Html::a(Icon::show('cloud', [], Icon::WHHG), ['someaction', 'id' => $i->id, 'currentPath' => Module::$currentPath], ['class' => 'btn btn-sm btn-info']) .
        Html::a(Icon::show('notificationbottom', [], Icon::WHHG), ['image-extra/', 'image_id' => $i->id], ['class' => $btnImageExtra]) .
        Html::a(Icon::show('user', [], Icon::WHHG), ['image-author/', 'image_id' => $i->id], ['class' => $btnImageAuthors]) .
        Html::a(Icon::show('resize', [], Icon::WHHG), ['image/save-versions', 'id' => $i->id, 'currentPath' => Module::$currentPath], ['class' => $btnImageThumbs]) .
        Html::button(Icon::show('remove', [], Icon::WHHG), ['class' => 'btn btn-sm btn-danger btnDeleteImage', 'data-image-id' => $i->id]),
        ['class' => 'btn-group btn-group-xs']
    );

    $row .= "</div></div></div>";
    if ((++$n % 6) == 0) {
        echo Html::tag("div", $row, ['class' => 'row']);
        $row = "";
    }
}
echo Html::tag("div", $row, ['class' => 'row']);


//////////////////////////////////////////////////////
// Dropzone for file uploading
//

echo DropZone::widget([
    'url' => Url::to(["image/upload", 'currentPath' => Module::$currentPath, 'gallery' => Module::$galleryName]),
    'name' => 'file',
    'htmlOptions' => ['class' => 'col-xs-12'],
    'options' => [
        'maxFilesize' => '8',
    ],
    'eventHandlers' => [
        'complete' => "function(file){
                this.removeFile(file);
                if (this.getUploadingFiles().length < 1) {
/*
                    $.pjax({
                        container: '#pjax-images',
                        url: location.pathname + '?currentPath=" . Module::$currentPath . "'
                    });
*/
                    location.href = location.pathname + '?currentPath=" . Module::$currentPath . "'
                }
            }",
        'removedfile' => "function(file){console.log(file.name + ' is removed. Queue: ' + this.getUploadingFiles().length)}"
    ],
]);

