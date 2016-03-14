<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/11/2016
 * Time: 1:21 AM
 */

use comradepashka\gallery\models\Album;
use comradepashka\gallery\Module;
use yii\bootstrap\Modal;
use yii\helpers\Html;

use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\web\JsExpression;


use kartik\icons\Icon;
use execut\widget\TreeView;
use devgroup\dropzone\DropZone;

/** @var Module $module */

$this->registerCss("
    .dropZone:hover { border: dashed }
    .thumb { height: 128px !important; width: 128px }
    .dirtoolbox { padding-bottom: 4px }
    .toolbox { opacity: 0.3; padding: 2px !important }
    .toolbox small { opacity: 1 !important }
    .toolbox:hover { opacity: 1 }
");

$this->registerJs('
    $("document").ready(function(){
        $.pjax.defaults.scrollTo = false,
        $.pjax.defaults.push = false;
        $.pjax.defaults.timeout = null;
        $(document).on("pjax:beforeSend", function(e, xhr, options) {
            if (e.relatedTarget != null) {
                if (e.relatedTarget.type.toLowerCase() == "post") {
                    options.type = "post";
                }
            }
        });
        $("#pjax-albums").on("pjax:end", function() {
            $.pjax({container: "#pjax-images"});
        });
    });
    ');

Modal::begin(['id' => 'popup-modal']);
echo Html::tag("div", 'test', ['id' => 'popup-modal-content']);
Modal::end();

//////////////////////////////////////////////////////
// Directory toolbox
//
Pjax::begin([
    'id' => 'pjax-albums',
    'options' => ['class' => 'col-xs-2'],
]);
$module = Module::getInstance();
$album = $module->galleries[$gallery]->CurrentAlbum;
$album->gallery->currentPath = $currentPath;

echo "<div>Gallery: $gallery</div>" .
    Html::tag("div",
    Html::a(Icon::show('createfolder', [], Icon::WHHG), ['create', 'currentPath' => $currentPath], [
        'id' => 'btnAlbumNew', 'class' => 'showModalButton btn btn-default', 'data-modal-pjax-callback-container' => '#pjax-albums'
    ]) .
    Html::a(Icon::show('systemfolder', [], Icon::WHHG), ['update', 'currentPath' => $currentPath], [
        'id' => 'btnAlbumEdit', 'class' => 'showModalButton btn btn-default btn-target disabled', 'data-modal-pjax-callback-container' => '#pjax-albums'
    ]) .
    Html::a(Icon::show('deletefolder', [], Icon::WHHG), ['delete', 'currentPath' => $currentPath], [
        'id' => 'btnAlbumDel', 'class' => 'showModalButton btn btn-danger btn-target disabled', 'data-modal-pjax-callback-container' => '#pjax-albums'
    ])
    , ['class' => 'dirtoolbox btn-group btn-group-sm']);

//////////////////////////////////////////////////////
// Directory treeview
//

echo TreeView::widget([
    'id' => 'albums',
    'data' => [$album->Tree],
    'template' => TreeView::TEMPLATE_SIMPLE,
    'size' => TreeView::SIZE_SMALL,
    'clientOptions' => [
        'levels' => 5,
        'showIcon' => true,
        'expandIcon' => 'glyphicon glyphicon-folder-close',
        'collapseIcon' => 'glyphicon glyphicon-folder-open',
        'onNodeSelected' => new JsExpression("function (event, item) {
            console.log('SEL!');
            if (item.nodeId == 0) $('.btn-target').addClass('disabled');
            else $('.btn-target').removeClass('disabled');
            $.pjax({
                container: '#pjax-images',
                url: location.pathname + '?currentPath=' + item.data,
            });
        }"),
        'onNodeUnselected' => new JsExpression("function (event, item) {
            if ($('#albums').treeview('getSelected') == '') {
                $.pjax({container: '#pjax-albums'});
            }
        }"),
    ],
]);
Pjax::end();

//////////////////////////////////////////////////////
// Image list for current folder
//
Pjax::begin([
    'id' => 'pjax-images',
    'options' => ['class' => 'col-xs-10'],
]);

$album->gallery->currentPath = $currentPath;
$images = $album->find($currentPath)->images;
echo "<div>Path: $currentPath</div>" .
    Html::tag("div",
        Html::a("Some buttons", ['create', 'currentPath' => $currentPath], [
            'id' => 'btnAlbum1', 'class' => 'showModalButton btn btn-default', 'data-modal-pjax-callback-container' => '#pjax-albums'
        ]) .
        Html::a(Icon::show('systemfolder', [], Icon::WHHG), ['update', 'currentPath' => $currentPath], [
            'id' => 'btnAlbum2', 'class' => 'showModalButton btn btn-default btn-target', 'data-modal-pjax-callback-container' => '#pjax-albums'
        ]) .
        Html::a(Icon::show('deletefolder', [], Icon::WHHG), ['delete', 'currentPath' => $currentPath], [
            'id' => 'btnAlbum3', 'class' => 'showModalButton btn btn-default btn-target', 'data-modal-pjax-callback-container' => '#pjax-albums'
        ])
        , ['class' => 'dirtoolbox btn-group btn-group-sm']);
$n = 0;
$row = "";

foreach ($images as $i) {
    $imageUrl = $i->getUrl();

    $allBtnClass = "showModalButton btn btn-images-extra";
    $versionBtnClass = "btnImageVersions ";
    /*
        if ($i->getState() == Image::IMAGE_STATE_NORMAL) {
    //            $versionBtnClass .= "btn-default";
        } else {
            $versionBtnClass .= "btn-warning";
        }
        if ($i->getState() & Image::IMAGE_STATE_ORIGINAL) {
            $allBtnClass .= " btn-default";
        } else {
            $allBtnClass .= " disabled";
            $imageUrl = Image::imagePlaceholder;
        }
        if ($i->getState() & Image::IMAGE_STATE_THUMBS_ALL) {
            $imageUrl = $i->getUrlVersion("small");
        } else {
            if ($i->getState() & Image::IMAGE_STATE_THUMBS) {
                $versionBtnClass .= "btn-info";
            } else {
            }
        }
    */
    $row .= "
<div class='col-xs-2'>
<div class='thumbnail'>
    <a href='#' title='{$i->getFileName()}'><img src='{$imageUrl}' class='thumb' /></a>
    <div class='text-center'><small>{$i->getShortFileName()}</small></div>
    <div class='caption text-center toolbox'>" .
        Html::tag('div',
            Html::a(Icon::show('edit', [], Icon::WHHG), ['/image-seo', 'image_id' => $i->id], [
                'class' => "btnImageEdit $allBtnClass"]) .
            Html::a(Icon::show('notificationbottom', [], Icon::WHHG), ['image/x', 'id' => $i->id], [
                'class' => "btnImageMeta $allBtnClass"]) .
            Html::a(Icon::show('user', [], Icon::WHHG), ['image/u', 'id' => $i->id], [
                'class' => "btnImageAuthors $allBtnClass"]) .
// , 'data-toggle' => 'modal', 'data-target' => '#imageAuthorModal'
            Html::a(Icon::show('resize', [], Icon::WHHG), ['image/save-versions', 'id' => $i->id], [
                'class' => "$allBtnClass$versionBtnClass"]) .
            Html::a(Icon::show('remove', [], Icon::WHHG), ['image/delete', 'id' => $i->id], [
                'class' => "btnImageDelete btn btn-danger", 'type' => 'post']),
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
// echo "<!-- " . json_encode($files) . " -->";

//////////////////////////////////////////////////////
// Dropzone for file uploading
//

echo DropZone::widget([
    'url' => Url::to(["image/upload", 'path' => $currentPath]),
//    'name' => 'file',
//    'htmlOptions' => ['class' => 'dropZone'],
    'options' => [
        'maxFilesize' => '8',
    ],
    'eventHandlers' => [
        'complete' => "function(file){
                this.removeFile(file);
                if (this.getUploadingFiles().length < 1) {
                    $.pjax({
                        container: '#pjax-images',
                        url: location.pathname + '?path={$currentPath}'
                    });
                }
            }",
        'removedfile' => "function(file){console.log(file.name + ' is removed. Queue: ' + this.getUploadingFiles().length)}"
    ],
]);
Pjax::end();
?>