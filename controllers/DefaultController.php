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
/*
        $galleries = $this->module->galleries;
        if (count($galleries) == 1 && isset($galleries['default'])) return $this->redirect(['album/']);
        else return $this->render('index');
 */
        if (count(Module::$galleries) == 1 && isset(Module::$galleries['default'])) return $this->redirect(['album/']);
        else return $this->render('index');
    }

    public function actionTest()
    {
        return $this->render('test.twig');
    }
    public function actionGalleryList2($gallery = 'default')
    {
        Module::$gallery = Module::$galleries[$gallery];
        return $this->renderPartial('index');
    }
}