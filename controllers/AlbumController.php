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

class AlbumController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionCreate($name=null)
    {
        if ($name) {
            $album = Module::$currentAlbum->create($name);
            if (!$album->hasErrors()){
                return "location:?currentPath=" . $album->path;
            } else {
                return "error:" . $album->getFirstError("path");
            }
        } else {
            return $this->renderAjax('create');
        }
    }
    public function actionUpdate($name=null)
    {
        $album = Module::$currentAlbum;
        if ($name) {
            $album->update($name);
            if (!$album->hasErrors()){
                return "location:?currentPath=" . $album->path;
            } else {
                return "error:" . $album->getFirstError("path");
            }
        } else {
            return $this->renderAjax('update');
        }
    }

    public function actionDelete()
    {
        $album = Module::$currentAlbum->delete();
        return "location:?currentPath=" . $album->path;
    }
}