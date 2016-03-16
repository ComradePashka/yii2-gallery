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

$this->registerJs("
    $('document').ready(function(){
        $('#pjax-albums').on('pjax:end', function(event, xhr, opt) {
            if (matches = opt.url.match(/\?currentPath=\/(&|$)/)) $('.btn-target').addClass('disabled');
            else $('.btn-target').removeClass('disabled');
            $.pjax({container: '#pjax-images', url: opt.url});
        });
    });
    ");

$tree = Module::$rootAlbum->Tree;

//////////////////////////////////////////////////////
// Directory toolbox
//
Pjax::begin([
    'id' => 'pjax-albums',
    'options' => ['class' => 'col-xs-2'],
]);

echo "<div>Gallery: " . Module::$gallery->name . "</div>" .
    Html::tag("div",
    Html::a(Icon::show('createfolder', [], Icon::WHHG), ['create', 'currentPath' => Module::$currentPath], [
        'id' => 'btnAlbumNew', 'class' => 'showModalButton btn btn-default', 'data-modal-pjax-callback-container' => '#pjax-albums'
    ]) .
    Html::a(Icon::show('systemfolder', [], Icon::WHHG), ['update', 'currentPath' => Module::$currentPath], [
        'id' => 'btnAlbumEdit', 'class' => 'showModalButton btn btn-default btn-target disabled', 'data-modal-pjax-callback-container' => '#pjax-albums'
    ]) .
    Html::a(Icon::show('deletefolder', [], Icon::WHHG), ['delete', 'currentPath' => Module::$currentPath], [
        'id' => 'btnAlbumDel', 'class' => 'showModalButton btn btn-danger btn-target disabled', 'data-modal-pjax-callback-container' => '#pjax-albums'
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
            $.pjax({
                container: '#pjax-albums',
                url: location.pathname + '?currentPath=' + item.data,
            });
        }")
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
echo $this->render('/image/index');
Pjax::end();

?>