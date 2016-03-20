<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/11/2016
 * Time: 8:51 PM
 */

namespace comradepashka\gallery\controllers;

use comradepashka\ajaxable\AjaxableBehaviour;
use Yii;
use yii\web\Controller;
use comradepashka\gallery\Module;

class AlbumController extends Controller
{
    public function behaviors() {
        return [
//            'class' => AjaxableBehaviour::class
        ];
    }
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionCreate($name=null)
    {
        if ($name) {
            $album = Module::$currentAlbum->create($name);
            if ($album->hasErrors()){
//                Module::$lastError = $album->getFirstError("path");
            } else {
//                Module::$currentPath = $album->path;
                return $this->redirect(['index', 'currentPath' => $album->path]);
            }
        }
        return $this->render('create');
    }
    public function actionUpdate($name=null)
    {
        $album = Module::$currentAlbum;
        yii::trace("STEP1 update a:" . $album->path . " new name: $name");
        if ($name) {
            $album->update($name);
            if ($album->hasErrors()){
                yii::trace("STEP2 error: $name");
                return $album->getFirstError("path") . "PISKA!";
//                Module::$lastError = $album->getFirstError("path");
            } else {
//              Module::$currentPath = $album->path;
                yii::trace("STEP2 success!!!");
                return $this->redirect(['index', 'currentPath' => $album->path]);
            }
        }
        yii::trace("STEP BULLSHIT!!!");
        return $this->render('update');
    }
    public function actionDelete()
    {
        $album = Module::$currentAlbum->delete();
        if ($album->hasErrors()) {
//            Module::$lastError = $album->getFirstError("path");
        }
//        Module::$currentPath = $album->path;
        return $this->redirect(['index', 'currentPath' => $album->path]);
    }
}