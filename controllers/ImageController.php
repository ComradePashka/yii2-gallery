<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/14/2016
 * Time: 5:35 AM
 */

namespace comradepashka\gallery\controllers;

use comradepashka\gallery\models\Image;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;

class ImageController extends Controller
{
    public $album;
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    public function actionIndex($gallery = 'default', $currentPath = '/')
    {
        return $this->render('index', ['gallery' => $gallery, 'currentPath' => $currentPath]);
    }
    public function actionUpload($gallery = 'default', $currentPath = '/')
    {
        $fileName = 'file';
        if (isset($_FILES[$fileName])) {
            $file = UploadedFile::getInstanceByName($fileName);
            $path = $this->module->galleries[$gallery]->WebRootPath . $currentPath . $file->name;
            $webPath =  $this->module->galleries[$gallery]->WebRoot . $currentPath . $file->name;
            if ($file->saveAs($path)) {
//                $image->saveVersions();
                $image = new Image(['webrootpath' => $webPath, 'gallery' => $this->module->galleries[$gallery]]);
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
        $path = $model->getFileDir();
        $model->removeVersions();
        $model->delete();
        return $this->render('index', ['path' => $path]);
    }
    public function actionSaveVersions($id)
    {
        $model = $this->findModel($id);
        $model->saveVersions();
        $path = $model->getFileDir();
        Yii::info('Saving versions for: ' . $path, 'images');
        return $this->render('index', ['path' => $path]) . "PATH:: $path";
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