<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/11/2016
 * Time: 8:51 PM
 */

namespace comradepashka\gallery\controllers;


use yii\web\Controller;
use comradepashka\gallery\Module;

class AlbumController extends Controller
{
    public function actionIndex($gallery = 'default')
    {
        return $this->render('index', ['gallery' => $gallery]);
    }
}