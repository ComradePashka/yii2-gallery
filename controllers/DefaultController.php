<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/11/2016
 * Time: 1:19 AM
 */

namespace comradepashka\gallery\controllers;

use comradepashka\gallery\models\Image;
use Yii;
use yii\web\Controller;
use comradepashka\gallery\Module;

class DefaultController extends Controller
{
    public function actionIndex()
    {
//        if ((count(Module::$_galleries) == 1) && (Module::$galleryName == 'default')) return $this->redirect(['album/']);
//        else return $this->render('index');
        return $this->render('index');
    }

    public function actionRegen()
    {
        return $this->render('index');
        yii::trace('REGEN STARTED!' . strftime('%H:%M'));
        $images = Image::find()->where("header is null")->limit(10)->all();
        $lines = "REGEN ALL IMAGES!!!<br />";
        $c=0;
        $l = count($images);
        foreach ($images as $i) {
            if (file_exists($i->RootPath)) {
                $i->header = "1";
                $i->save();
                $lines .= "{$i->path}[+]<br />";
                $c++;
            } else {
                $lines .= "File doesn't exist! {$i->path}[-]<br />";
            }
        }
        yii::trace('REGEN FINISHED!' . strftime('%H:%M'));
        if ($l > 1) Yii::$app->response->headers->set('refresh', '3');
        return "TOTAL: $l READY: $c YO2!<br />$lines";
    }

    public function actionCreateAlbum($name)
    {
        if (@mkdir(Module::getGallery()->WebRootPath . Module::$currentPath . $name))
            return $this->redirect(['index', 'currentPath' => Module::$currentPath . $name . "/"]);
        else {
            $this->addError("path", "Can not create album: " . $this->WebRootPath . $name);
            return $this;
        }
    }

    public function actionRefresh()
    {
        Yii::$app->response->headers->set('refresh', '5');
        return "5sec to go!";
    }

    public function actionView()
    {
        return $this->render('view');
    }

    public function actionTest()
    {
        return $this->render('test.twig');
    }
}