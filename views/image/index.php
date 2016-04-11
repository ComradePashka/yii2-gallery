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

use comradepashka\gallery\models\Image;
use comradepashka\gallery\Module;

use kartik\icons\Icon;
use devgroup\dropzone\DropZone;


$this->registerJs("
    $('#btnDelAll').on('click', function (e) {
        if (confirm('УДАЛИТЬ??? ТОЧНЯК?')) {
            location.href = '" . Url::to(['default/delete-all', 'currentPath' => Module::$currentPath, 'gallery' => Module::$galleryName]) . "';
        }
    });
    $('#btnDeleteImage').on('click', function (e) {
        if (confirm('УДАЛИТЬ???')) {
            location.href = '" . Url::to(['image/delete', 'id' => $i->id, 'currentPath' => Module::$currentPath, 'gallery' => Module::$galleryName]) . "';
        }
    });

");


/*
echo Html::tag("div",
        Html::a("Some buttons", ['create', 'currentPath' => Module::$currentPath], [
            'id' => 'btnAlbum1', 'class' => 'btn btn-default', 'data-modal-pjax-callback-container' => '#pjax-albums'
        ]) .
        Html::a(Icon::show('systemfolder', [], Icon::WHHG), ['update', 'currentPath' => Module::$currentPath], [
            'id' => 'btnAlbum2', 'class' => 'btn btn-default btn-target', 'data-modal-pjax-callback-container' => '#pjax-albums'
        ]) .
        Html::a(Icon::show('deletefolder', [], Icon::WHHG), ['delete', 'currentPath' => Module::$currentPath], [
            'id' => 'btnAlbum3', 'class' => 'btn btn-default btn-target', 'data-modal-pjax-callback-container' => '#pjax-albums'
        ])
        , ['class' => 'btn-group btn-group-sm']);
*/

$n = 0;

$images = Module::getImages();

$row = Html::tag("div",
        Html::button('<span class="glyphicon glyphicon-remove"></span>DELETE ALL!!!',['id' => 'btnDelAll', 'class' => 'btn btn-danger']),
    ['class' => 'row' . ((count($images) == 0) ? " hidden" : "" )]);


foreach ($images as $i) {
    $allBtnClass = "btn";
    $btnImageSEO = $btnImageExtra = $btnImageAuthors = $btnImageThumbs = $btnImageAdd = $btnImageDelete = $allBtnClass;

//    if ($i->title)
//    else $btnImageSEO .= " btn-warning";
    $btnImageSEO .= " btn-info";
    $btnImageExtra = $btnImageThumbs .= " btn-info";
//    if ($i->ImageExtra) $btnImageSEO .= " btn-default"; else $btnImageSEO .= " btn-warning";
    if ($i->imageAuthors) $btnImageAuthors .= " btn-info"; else $btnImageAuthors .= " btn-warning";
    $btnImageAdd .= " btn-success";
    $btnImageDelete .= " btn-danger";

    $activeImage = "thumb-image";
    /*
        if ((Module::$currentImage->id) && (Module::$currentImage->id == $i->id)) {
            $activeImage .= " thumb-image-active";
        }
    */
    $state = "";
    $buttonAddDel = "";
    switch ($i->State) {
        case Image::STATE_EMPTY:
            $imageUrl = Module::getGallery()->Placeholder;
            $state = "EMPTY";
            break;
        case Image::STATE_UNSAVED:
            $imageUrl = $i->webrootpath;
            $state = "UNSAVED";
            $buttonAddDel = Html::a(Icon::show('plus', [], Icon::WHHG), ['image/add', 'path' => $i->path], [
                'class' => $btnImageAdd, 'data-modal-pjax-callback-container' => '#pjax-images']);
            break;
        case Image::STATE_BROKEN:
            $imageUrl = Module::getGallery()->Placeholder;
            $state = "BROKEN";
            break;
        default:
            $imageUrl = $i->webrootpath;
            $state = "NORMAL";
            $buttonAddDel = Html::a(Icon::show('remove', [], Icon::WHHG), ['image/delete', 'id' => $i->id], [
                'class' => $btnImageDelete, 'data-modal-pjax-callback-container' => '#pjax-images']);
            break;
    }

    $versions = [];
    foreach (Module::getGallery()->Versions as $name => $func) {
        $url = $i->getWebVersionPath($name);
        $versions[] = "<li><a href='{$url}' data-origin='{$i->WebRootPath}' data-image-id='{$i->id}' data-image-ver='{$name}'>{$name}</a></li>";
    }

// btn-default btn-info btn-warning disabled
// , 'data-modal-pjax-callback-container' => '#pjax-images'

    $row .= "
<div class='col-xs-2'>
<div class='thumbnail'>" .
Html::a(Html::img($i->getWebVersionPath("-small"), ['class' => 'thumb' ,'title' => $i->Name]),
['image/update', 'id' => $i->id, 'currentPath' => Module::$currentPath]) .
"<div class='caption text-center'>{$i->Name}<div class='row'>" .
 Html::a('<span class="glyphicon glyphicon-share"></span>', ['default/clone-meta', 'id' => $i->id, 'currentPath' => Module::$currentPath], ['id' => 'btnCloneMeta', 'class' => 'btn btn-sm btn-default']) .
 Html::button('<span class="glyphicon glyphicon-remove"></span>', ['id' => 'btnDeleteImage', 'class' => 'btn btn-sm btn-danger']) .
"</div><div class='progress zero-margin'>
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
/*
    $row .=
        Html::tag('div',
            Html::a(Icon::show('edit', [], Icon::WHHG), ['image/index', 'image_id' => $i->id, 'currentPath' => Module::$currentPath, 'imagePlugin' => 'seo'],
                ['class' => $btnImageSEO]) .
            Html::a(Icon::show('notificationbottom', [], Icon::WHHG), ['image/extra', 'id' => $i->id, 'currentPath' => Module::$currentPath],
                ['class' => $btnImageExtra]) .
            Html::a(Icon::show('user', [], Icon::WHHG), ['image/authors', 'id' => $i->id, 'currentPath' => Module::$currentPath],
                ['class' => $btnImageAuthors]) .
            Html::a(Icon::show('resize', [], Icon::WHHG), ['image/save-versions', 'id' => $i->id, 'currentPath' => Module::$currentPath],
                ['class' => $btnImageThumbs]) .
            $buttonAddDel,
            ['class' => 'btn-group btn-group-xs']
        ) .
*/
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

