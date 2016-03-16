<?php
namespace comradepashka\gallery;

use yii\web\AssetBundle;

class ToolsAsset extends AssetBundle
{
    public $sourcePath = '@comradepashka/gallery/assets';
    public $css = [
        'main.css'
    ];
    public $js = [
        'ajax-modal-popup.js',
        'pjax-config.js'
    ];
}
