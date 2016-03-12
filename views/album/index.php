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

$module = Module::getInstance();
$album = new Album();

$this->registerCss("
    .dropZone:hover { border: dashed }
    .thumb { height: 128px !important; width: 128px }
    .dirtoolbox { padding-bottom: 4px }
    .toolbox { opacity: 0.3; padding: 2px !important }
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
        $("#btn-save-album").on("click", function() {
            $("#albumModal").modal("hide");
            $.pjax({
                container: "#pjax-albums",
                url: $("#albumModal").attr("url").replace(/([?&])(name=)[^&]*/, "$1$2" + $("#albumName").val()),
            });
        });
        $("#albumModal").on("show.bs.modal", function(e) {
            $("#albumModal").attr("url", e.relatedTarget);
            switch(e.relatedTarget.id) {
              case "btnAlbumNew": $("#albumName").val(""); break;
              case "btnAlbumEdit": $("#albumName").val($("#albums").treeview(true).getSelected()[0].text); break;
            }
        });
    });
    ');

echo "<h3>$gallery</h3>";
?>
<div class="row">
    <div class="col-sm-3">
        <?php
        //////////////////////////////////////////////////////
        // Directory toolbox
        //
        Modal::begin([
            'id' => 'albumModal',
            'header' => 'Album name',
        ]);
        echo
        Html::tag("div",
            Html::input('text', 'name', null, ['id' => 'albumName', 'class' => 'form-control']) .
            Html::tag('span',
                Html::button('Save', ['id' => 'btn-save-album', 'class' => 'btn btn-primary']) .
                Html::button('Cancel', ['class' => 'btn btn-primary', 'data-dismiss' => 'modal']),
                ['class' => 'input-group-btn']
            ),
            ['class' => 'input-group']
        );
        Modal::end();

        Pjax::begin([
            'id' => 'pjax-albums',
            'enablePushState' => false,
        ]);
        echo Html::tag("div",
            Html::a(Icon::show('createfolder', [], Icon::WHHG), ['image/album-new'], [
                'id' => 'btnAlbumNew', 'class' => 'btn btn-default', 'data-toggle' => 'modal', 'data-target' => '#albumModal'
            ]) .
            Html::a(Icon::show('systemfolder', [], Icon::WHHG), ['image/album-edit'], [
                'id' => 'btnAlbumEdit', 'class' => 'btn btn-default btn-target disabled', 'data-toggle' => 'modal', 'data-target' => '#albumModal'
            ]) .
            Html::a(Icon::show('deletefolder', [], Icon::WHHG), ['image/album-del', 'path' => $currentPath], [
                'id' => 'btnAlbumDel', 'class' => 'btn btn-danger btn-target disabled'
            ]),
            ['class' => 'dirtoolbox btn-group btn-group-sm']
        );


        //////////////////////////////////////////////////////
        // Directory treeview
        //

        //$albums = Image::getAlbums();
        //Image::selectAlbum($albums, $path);

        echo TreeView::widget([
            'id' => 'albums',
            'data' => [$album->getTree($currentPath)],
            'header' => "$currentPath",
            'size' => TreeView::SIZE_SMALL,
            'clientOptions' => [
                'levels' => 5,
                'showIcon' => true,
                'expandIcon' => 'glyphicon glyphicon-folder-close',
                'collapseIcon' => 'glyphicon glyphicon-folder-open',
                'onNodeSelected' => new JsExpression("function (event, item) {
                console.log('SEL!!');
                $('.btn-target').removeClass('disabled');
                /*
                $.pjax({
                    container: '#pjax-albums',
                    url: location.pathname + '?currentPath=' + item.data,
                });
                $('#pjax-albums').one('pjax:end', function() {
                    $.pjax({
                        container: '#pjax-images',
                        url: location.pathname + '?currentPath=' + item.data,
                    });
                });
                */
                $.pjax({
                    container: '#pjax-images',
                    url: location.pathname + '?currentPath=' + item.data,
                });
        }"),
                'onNodeUnselected' => new JsExpression("function (event, item) {
                if ($('#albums').treeview('getSelected') == '') $('.btn-target').addClass('disabled');
        }"),
            ],
        ]);

        Pjax::end();
        ?>
    </div>
    <?php

    //////////////////////////////////////////////////////
    // Image list for current folder
    //

    Pjax::begin([
        'id' => 'pjax-images',
//        'enablePushState' => false,
        'options' => ['class' => 'col-sm-9'],
//        'clientOptions' => ['container' => '#pjax-imagesExtra']
    ]);

    $n = 0;
    $row = "";

    //$images = Image::find()->where(['rlike', 'path', "^{$path}[^/]+$"])->all();
    //$files = Image::getImages($path);

    $images = $album->images;

    foreach ($images as $i) {
        /*
            foreach ($files as $f) {
                if ($i->getUrl() === $f['url']) {
                    unset($f);
                }
            }
        */
        $imageUrl = $album->getWebPath() . $i->getUrl();


        $allBtnClass = "btn btn-images-extra";
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
<div class='col-sm-2'>
<div class='thumbnail'>
    <a href='#' data-toggle='tooltip' data-placement='bottom' title='{$i->getFileName()}'><img src='{$imageUrl}' class='thumb' /></a>
    <div class='caption text-center toolbox'>
    <small>{$i->getShortFileName()}</small>" .
            Html::tag('div',
                Html::a(Icon::show('edit', [], Icon::WHHG), ['/image-seo', 'image_id' => $i->id], [
                    'class' => "showModalButton btnImageEdit $allBtnClass", 'data-modal' => '#imageSeoModal', 'data-title' => 'newnwnenwnewnew!!!']) .
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
        'name' => 'file',
        'htmlOptions' => ['class' => 'dropZone'],
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
</div>