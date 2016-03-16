<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/14/2016
 * Time: 5:35 AM
 */

namespace comradepashka\gallery\controllers;

use comradepashka\gallery\models\Image;
use comradepashka\gallery\Module;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;

class ImageController extends Controller
{
    public $album;
/*
 *
     public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => ['delete' => ['post'],],
            ],
        ];
    }

 */
    public function actionIndex($gallery = 'default', $currentPath = '/')
    {
        return $this->render('index', ['gallery' => $gallery, 'currentPath' => $currentPath]);
//        return $this->render('index', ['gallery' => $gallery, 'currentPath' => $currentPath]);
    }
//    public function actionUpload($gallery = 'default', $currentPath = '/')
    public function actionUpload()
    {
        $fileName = 'file';
        if (isset($_FILES[$fileName])) {
            $file = UploadedFile::getInstanceByName($fileName);
            $path = Module::$gallery->WebRootPath . Module::$currentPath . $file->name;
            $webPath =  Module::$gallery->WebRoot . Module::$currentPath . $file->name;
            if ($file->saveAs($path)) {
//                $image->saveVersions();
                $image = new Image(['webrootpath' => $webPath]);
                if ($image->isNewRecord) $image->save(); else $image->update();
                return Json::encode($file);
            }
        }
        return "???";
    }
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $path = $model->ParentPath;
        $model->delete();
        return "location:?currentPath=" . $path;
//        return $this->render('index', ['path' => $path]);
    }

    public function actionAdd($path)
    {
        $model = new Image(['path' => $path]);
        $model->save();
        return "location:?currentPath=" . $model->ParentPath;
    }


    public function actionSaveVersions($id)
    {
        $model = $this->findModel($id);
        $model->saveVersions();
        $path = $model->getFileDir();
        Yii::info('Saving versions for: ' . $path, 'images');
        return $this->render('index', ['path' => $path]) . "PATH:: $path";
    }


    public function actionUpdate($gallery = 'default', $currentPath = '/')
    {
        $album = $this->module->galleries[$gallery]->rootAlbum->find($currentPath);
        if ($name = yii::$app->request->post('name')) {
            $album->update($name);
            if (!$album->hasErrors()){
                return "location:?currentPath=" . $album->path;
            } else {
                return "error: " . $album->getFirstError("path");
            }
        } else {
            return $this->renderAjax('update', ['gallery' => $gallery, 'currentPath' => $currentPath, 'name' => $album->Name]);
        }
    }

    protected function findModel($id)
    {
        if (($model = Image::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}