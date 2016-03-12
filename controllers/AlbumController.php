<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/11/2016
 * Time: 8:51 PM
 */

namespace comradepashka\gallery\controllers;


use Yii;
use yii\web\Controller;
use comradepashka\gallery\Module;
use comradepashka\gallery\models\Album;

class AlbumController extends Controller
{
    public function actionIndex($gallery = 'default', $currentPath = '/')
    {
//        Album::setGalleryRootPath($this->module->galleries[$gallery]['root']);
//        Album::setGalleryWebPath($this->module->galleries[$gallery]['webRoot']);
        Album::setGalleryConfig($this->module->galleries[$gallery]);
        return $this->render('index', ['gallery' => $gallery, 'currentPath' => $currentPath]);
    }
}