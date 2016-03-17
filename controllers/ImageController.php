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


class ImageController extends Controller
{
    public $album;

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionUpload()
    {
        $fileName = 'file';
        if (isset($_FILES[$fileName])) {
            $file = UploadedFile::getInstanceByName($fileName);
            $path = Module::$gallery->WebRootPath . Module::$currentPath . $file->name;
            $webPath = Module::$gallery->WebRoot . Module::$currentPath . $file->name;
            if ($file->saveAs($path)) {
                $image = new Image(['webrootpath' => $webPath]);
                if ($image->isNewRecord) $image->save(); else $image->update();
                return Json::encode($file);
            }
        }
        return "?";
    }

    /*
        public function actionView($id)
        {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $path = $model->ParentPath;
        $model->delete();
        return "location:?currentPath=" . $path;
    }

    public function actionAdd($path)
    {
        $model = new Image(['WebRootPath' => $path]);
        $model->save();
        return "location:?currentPath=" . $model->ParentPath;
    }

/*
    public function actionSeo($id)
    {
        if ($image = Image::findOne($id)) {
            return $this->renderAjax('/image-seo/index', ['image' => $image]);
        } else {
            return "error:image($id) not found!";
        }
    }
*/
    public function actionSaveVersions($id)
    {
        $model = $this->findModel($id);
        $model->saveVersions();
        $path = $model->ParentPath;
        Yii::info('Saving versions for: ' . $path, 'images');
        return $this->render('index', ['path' => $path]) . "PATH:: $path";
    }


    public function actionUpdate($gallery = 'default', $currentPath = '/')
    {
        $album = $this->module->galleries[$gallery]->rootAlbum->find($currentPath);
        if ($name = yii::$app->request->post('name')) {
            $album->update($name);
            if (!$album->hasErrors()) {
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