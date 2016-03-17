<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/17/2016
 * Time: 11:54 AM
 */

namespace comradepashka\gallery\controllers;


use yii\web\Controller;

class GalleryBaseController extends Controller
{

/**
* @param string $view
* @param array $params
* @return string
* @throws InvalidParamException
*/
    public function render($view, $params = [])
    {

        return Yii::$app->request->isAjax ?
            parent::renderAjax($view, $params) :
            parent::render($view, $params);
    }
}