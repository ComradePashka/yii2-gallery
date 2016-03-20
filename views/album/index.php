<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/11/2016
 * Time: 1:21 AM
 */

//use comradepashka\gallery\models\Album;
//use comradepashka\gallery\models\Image;
use comradepashka\gallery\Module;
use yii\helpers\Html;

use yii\widgets\Pjax;
use yii\web\JsExpression;

use kartik\icons\Icon;
use execut\widget\TreeView;

/** @var Module $module */

/*
$this->registerJs("
    $('document').ready(function(){
        $('#pjax-albums').on('pjax:end', function(event, xhr, opt) {
            if (matches = opt.url.match(/currentPath=\/(&|$)/)) $('.btn-target').addClass('disabled');
            else $('.btn-target').removeClass('disabled');
            if (event.relatedTarget != undefined) {
                switch (event.relatedTarget.attributes['cascade'].value) {
                case 'no':
                    $.pjax({container: '#pjax-albums'});
                    return;
                case 'wait':
                    return;
                default:
                    $.pjax({container: '#pjax-albums'});
                    break;
                }
            }
            $.pjax({container: '#pjax-images', url: opt.url});
        });
    });
");

*/

//////////////////////////////////////////////////////
// Directory toolbox
//
/*
Pjax::begin([
    'id' => 'pjax-albums',
    'options' => ['class' => 'col-xs-2'],
    'enablePushState' => false,
]);
*/

$tree = Module::$rootAlbum->Tree;
$btnEditable = (Module::$currentPath == "/") ? "disabled" : "";
$error = Module::$lastError ? "Error: " . Module::$lastError : "";
Module::$lastError = "";
echo "<div>Gallery: " . Module::$gallery->name . "$error</div>" .
    Html::tag("div",
//
// , url: '?currentPath=" . Module::$currentPath ."'

// , 'currentPath' => Module::$currentPath
// 'data-modal-pjax-callback-container' => '#pjax-albums',
//
    Html::a(Icon::show('createfolder', [], Icon::WHHG), ['create', 'currentPath' => Module::$currentPath], [
        'id' => 'btnAlbumNew', 'class' => 'btn btn-default', 'cascade' => 'wait'
    ]) .
    Html::a(Icon::show('systemfolder', [], Icon::WHHG), ['update', 'currentPath' => Module::$currentPath], [
        'id' => 'btnAlbumEdit', 'class' => 'btn btn-default $btnEditable', 'cascade' => 'wait'
    ]) .
    Html::a(Icon::show('deletefolder', [], Icon::WHHG), ['delete', 'currentPath' => Module::$currentPath], [
        'id' => 'btnAlbumDel', 'class' => 'btn btn-danger $btnEditable', 'cascade' => 'no'
    ])
    , ['class' => 'dirtoolbox btn-group btn-group-sm']);

//////////////////////////////////////////////////////
// Directory treeview
//

echo TreeView::widget([
    'id' => 'albums',
    'data' => [$tree],
    'template' => TreeView::TEMPLATE_SIMPLE,
    'size' => TreeView::SIZE_SMALL,
    'clientOptions' => [
        'levels' => 5,
        'showIcon' => true,
        'expandIcon' => 'glyphicon glyphicon-folder-close',
        'collapseIcon' => 'glyphicon glyphicon-folder-open',
        'onNodeSelected' => new JsExpression("function (event, item) {
            document.location.href = location.pathname + '?currentPath=' + item.data;
/*
            $.pjax({
                container: '#pjax-albums',
                url: location.pathname + '?currentPath=' + item.data,
            });
*/
        }")
    ],
]);

//Pjax::end();

//////////////////////////////////////////////////////
// Image list for current folder
//
/*

<div class="col-sm-10">
    <div class="row">
    <?= $this->render('/image/index') ?>
    </div>
</div>

*/
?>
