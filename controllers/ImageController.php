<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/14/2016
 * Time: 5:35 AM
 */

namespace comradepashka\gallery\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;

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

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionUpload($currentPath)
    {
        $fileName = 'file';
        if (isset($_FILES[$fileName])) {
            $file = UploadedFile::getInstanceByName($fileName);
            if ($file->saveAs($this->gallery . $currentPath . $file->name)) {
                //Now save file data to database
                Yii::info('Saving model: ' . $path . $file->name, 'images');
                $image = new Image();
                $image->path = $path . $file->name;
                $image->saveVersions();
                $image->save();
                echo Json::encode($file);
            }
        }
        return false;
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