<?php
namespace comradepashka\gallery;

use yii\web\AssetBundle;

class GalleryAsset extends AssetBundle
{
    public $sourcePath = '@comradepashka/gallery/assets';
    public $css = [
        'main.css'
    ];
    public $js = [
        'pjax-config.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'yii\bootstrap\BootstrapThemeAsset',
    ];
}
