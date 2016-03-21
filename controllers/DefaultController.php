<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/11/2016
 * Time: 1:19 AM
 */

namespace comradepashka\gallery\controllers;

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

    public function actionView()
    {
        return $this->render('view');
    }

    public function actionTest()
    {
        return $this->render('test.twig');
    }
}