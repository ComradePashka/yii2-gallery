<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/15/2016
 * Time: 6:43 PM
 */

use comradepashka\gallery\models\ImageSeo;
use yii\data\ActiveDataProvider;
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
    'enablePushState' => false,
]);

$plugin = "";
//if ( != "") {
switch (Module::$imagePlugin) {
    case "seo":
        if (Module::$currentImage->id) {
            $dataProvider = new ActiveDataProvider([
                'query' => ImageSeo::find()->andWhere(['image_id' => Module::$currentImage->id]),
//                'query' => Module::$currentImage->id ? ImageSeo::find()->andWhere(['image_id' => Module::$currentImage->id]) : ImageSeo::find(),
            ]);
            $plugin = $this->render("/image-" . Module::$imagePlugin . "/index", ['dataProvider' => $dataProvider, 'image_id' => $image_id]);
        }
        break;
    case "extra":
        break;
    case "authors":
        break;
}
//if (Module::$imagePlugin != "") $plugin = "<div class='row'>$plugin</div>";



$images = Module::$currentAlbum->Images;
//yii::trace("Current image:" . Module::$currentImage->path);

echo "<div>Path: " . Module::$currentPath . " Plugin: " . Module::$imagePlugin . " cImagePath: " . Module::$currentImage->path . "</div>" .
    $plugin .
    Html::tag("div",
        Html::a("Some buttons", ['create', 'currentPath' => Module::$currentPath], [
            'id' => 'btnAlbum1', 'class' => 'btn btn-default', 'data-modal-pjax-callback-container' => '#pjax-albums'
        ]) .
        Html::a(Icon::show('systemfolder', [], Icon::WHHG), ['update', 'currentPath' => Module::$currentPath], [
            'id' => 'btnAlbum2', 'class' => 'btn btn-default btn-target', 'data-modal-pjax-callback-container' => '#pjax-albums'
        ]) .
        Html::a(Icon::show('deletefolder', [], Icon::WHHG), ['delete', 'currentPath' => Module::$currentPath], [
            'id' => 'btnAlbum3', 'class' => 'btn btn-default btn-target', 'data-modal-pjax-callback-container' => '#pjax-albums'
        ])
        , ['class' => 'dirtoolbox btn-group btn-group-sm']);
$n = 0;
$row = "";

foreach ($images as $i) {
    $allBtnClass = "btn";
    $btnImageSEO = $btnImageExtra = $btnImageAuthors = $btnImageThumbs = $btnImageAdd = $btnImageDelete = $allBtnClass;

    if ($i->imageSEO) $btnImageSEO .= " btn-info"; else $btnImageSEO .= " btn-warning";
    $btnImageExtra = $btnImageThumbs .= " btn-info";
//    if ($i->ImageExtra) $btnImageSEO .= " btn-default"; else $btnImageSEO .= " btn-warning";
    if ($i->imageAuthors) $btnImageAuthors .= " btn-info"; else $btnImageAuthors .= " btn-warning";
    $btnImageAdd .= " btn-success";
    $btnImageDelete .= " btn-danger";

    $activeImage = "thumb-image";
    if ((Module::$currentImage->id) && (Module::$currentImage->id == $i->id)) {
        $activeImage .= " thumb-image-active";
    }

    $state = "";
    $buttonAddDel = "";
    switch ($i->State) {
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
<div class='thumbnail {$activeImage}'>
    <a href='#' title='{$i->Name}'><img src='{$imageUrl}' class='thumb' /></a>
    <div class='text-center'><small>{$i->ShortFileName}</small></div>
    <div class='text-center'><small>{$state} -> {$i->ParentPath}</small></div>
    <div class='caption text-center toolbox'>" .
        Html::tag('div',
            Html::a(Icon::show('edit', [], Icon::WHHG), ['image/index', 'image_id' => $i->id, 'currentPath' => Module::$currentPath, 'imagePlugin' => 'seo'], [
                'class' => $btnImageSEO, 'data-modal-pjax-callback-container' => '#pjax-images']) .
            Html::a(Icon::show('notificationbottom', [], Icon::WHHG), ['image/extra', 'id' => $i->id, 'currentPath' => Module::$currentPath], [
                'class' => $btnImageExtra, 'data-modal-pjax-callback-container' => '#pjax-images']) .
            Html::a(Icon::show('user', [], Icon::WHHG), ['image/authors', 'id' => $i->id, 'currentPath' => Module::$currentPath], [
                'class' => $btnImageAuthors, 'data-modal-pjax-callback-container' => '#pjax-images']) .
            Html::a(Icon::show('resize', [], Icon::WHHG), ['image/save-versions', 'id' => $i->id, 'currentPath' => Module::$currentPath], [
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
Pjax::end();

//////////////////////////////////////////////////////
// Dropzone for file uploading
//

echo DropZone::widget([
    'url' => Url::to(["image/upload", 'currentPath' => Module::$currentPath, 'gallery' => Module::$gallery->name]),
    'name' => 'file',
    'htmlOptions' => ['class' => 'col-xs-12'],
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

