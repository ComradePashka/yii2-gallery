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
    public function actionIndex($gallery = 'default', $currentPath = '/')
    {
        return $this->render('index', ['gallery' => $gallery, 'currentPath' => $currentPath]);
    }
    public function actionCreate($gallery = 'default', $currentPath = '/')
    {
        if ($name = yii::$app->request->post('name')) {
            $album = $this->module->galleries[$gallery]->rootAlbum->find($currentPath)->create($name);
            if (!$album->hasErrors()){
                return "ok";
            } else {
                return "error: " . $album->getFirstError("path");
            }
        } else {
            return $this->renderAjax('create', ['gallery' => $gallery, 'currentPath' => $currentPath]);
        }
    }
    public function actionUpdate($gallery = 'default', $currentPath = '/')
    {
        $album = $this->module->galleries[$gallery]->rootAlbum->find($currentPath);
        if ($name = yii::$app->request->post('name')) {
            $album->update($name);
            if (!$album->hasErrors()){
                return "ok";
            } else {
                return "error: " . $album->getFirstError("path");
            }
        } else {
            return $this->renderAjax('update', ['gallery' => $gallery, 'currentPath' => $currentPath, 'name' => $album->Name]);
        }
    }

    public function actionDelete($gallery = 'default', $currentPath = '/')
    {
        $album = $this->module->galleries[$gallery]->rootAlbum->find($currentPath)->delete();
        return "ok";
    }
}