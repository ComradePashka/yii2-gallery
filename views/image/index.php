<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/15/2016
 * Time: 6:43 PM
 */
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\Html;
//use yii\web\JsExpression;

use comradepashka\gallery\models\Album;
use comradepashka\gallery\models\Image;
use comradepashka\gallery\Module;

use kartik\icons\Icon;
use devgroup\dropzone\DropZone;

Pjax::begin([
    'id' => 'pjax-images',
    'options' => ['class' => 'col-xs-12'],
]);

$images = Module::$currentAlbum->Images;

echo "<div>Path: " . Module::$currentPath . "</div>" .
    Html::tag("div",
        Html::a("Some buttons", ['create', 'currentPath' => Module::$currentPath], [
            'id' => 'btnAlbum1', 'class' => 'showModalButton btn btn-default', 'data-modal-pjax-callback-container' => '#pjax-albums'
        ]) .
        Html::a(Icon::show('systemfolder', [], Icon::WHHG), ['update', 'currentPath' => Module::$currentPath], [
            'id' => 'btnAlbum2', 'class' => 'showModalButton btn btn-default btn-target', 'data-modal-pjax-callback-container' => '#pjax-albums'
        ]) .
        Html::a(Icon::show('deletefolder', [], Icon::WHHG), ['delete', 'currentPath' => Module::$currentPath], [
            'id' => 'btnAlbum3', 'class' => 'showModalButton btn btn-default btn-target', 'data-modal-pjax-callback-container' => '#pjax-albums'
        ])
        , ['class' => 'dirtoolbox btn-group btn-group-sm']);
$n = 0;
$row = "";

foreach ($images as $i) {
    $allBtnClass = "showModalButton btn";
    $btnImageSEO = $btnImageExtra = $btnImageAuthors = $btnImageThumbs = $btnImageAdd = $btnImageDelete = $allBtnClass;

    if ($i->imageSEO) $btnImageSEO .= " btn-info"; else $btnImageSEO .= " btn-warning";
    $btnImageExtra = $btnImageThumbs .= " btn-info";
//    if ($i->ImageExtra) $btnImageSEO .= " btn-default"; else $btnImageSEO .= " btn-warning";
    if ($i->imageAuthors) $btnImageAuthors .= " btn-info"; else $btnImageAuthors .= " btn-warning";
    $btnImageAdd .= " btn-success";
    $btnImageDelete .= " btn-danger";

    $state = "";
    $buttonAddDel = "";
    switch($i->State) {
        case Image::STATE_EMPTY:
            $imageUrl = $i->gallery->Placeholder;
            $state = "EMPTY";
            break;
        case Image::STATE_UNSAVED:
            $imageUrl = $i->webrootpath;
            $state = "UNSAVED";
            $buttonAddDel = Html::a(Icon::show('plus', [], Icon::WHHG), ['image/add', 'path' => $i->path], [
                'class' => $btnImageAdd, 'data-modal-pjax-callback-container' => '#pjax-images']);
            break;
        case Image::STATE_BROKEN:
            $imageUrl = $i->gallery->Placeholder;
            $state = "BROKEN";
            break;
        default:
            $imageUrl = $i->webrootpath;
            $state = "NORMAL";
            $buttonAddDel = Html::a(Icon::show('remove', [], Icon::WHHG), ['image/delete', 'id' => $i->id], [
                'class' => $btnImageDelete, 'data-modal-pjax-callback-container' => '#pjax-images']);
            break;
    }

// btn-default btn-info btn-warning disabled
    $row .= "
<div class='col-xs-2'>
<div class='thumbnail'>
    <a href='#' title='{$i->Name}'><img src='{$imageUrl}' class='thumb' /></a>
    <div class='text-center'><small>{$i->ShortFileName}</small></div>
    <div class='text-center'><small>{$state} -> {$i->ParentPath}</small></div>
    <div class='caption text-center toolbox'>" .
        Html::tag('div',
            Html::a(Icon::show('edit', [], Icon::WHHG), ['image/seo', 'id' => $i->id], [
                'class' => $btnImageSEO, 'data-modal-pjax-callback-container' => '#pjax-images']) .
            Html::a(Icon::show('notificationbottom', [], Icon::WHHG), ['image/extra', 'id' => $i->id], [
                'class' => $btnImageExtra, 'data-modal-pjax-callback-container' => '#pjax-images']) .
            Html::a(Icon::show('user', [], Icon::WHHG), ['image/authors', 'id' => $i->id], [
                'class' => $btnImageAuthors, 'data-modal-pjax-callback-container' => '#pjax-images']) .
            Html::a(Icon::show('resize', [], Icon::WHHG), ['image/save-versions', 'id' => $i->id], [
                'class' => $btnImageThumbs, 'data-modal-pjax-callback-container' => '#pjax-images']) .
            $buttonAddDel,
            ['class' => 'btn-group btn-group-xs']
        ) .
        "</div>
    </div>
</div>";
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
    'url' => Url::to(["image/upload", 'currentPath' => Module::$currentPath, 'gallery' => Module::$gallery->name]),
    'name' => 'file',
    'options' => [
        'maxFilesize' => '8',
    ],
    'eventHandlers' => [
        'complete' => "function(file){
                this.removeFile(file);
                if (this.getUploadingFiles().length < 1) {
                    $.pjax({
                        container: '#pjax-images',
                        url: location.pathname + '?currentPath=" . Module::$currentPath . "'
                    });
                }
            }",
        'removedfile' => "function(file){console.log(file.name + ' is removed. Queue: ' + this.getUploadingFiles().length)}"
    ],
]);
Pjax::end();