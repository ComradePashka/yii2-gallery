<?php

namespace comradepashka\gallery\controllers;

use Yii;
use comradepashka\gallery\models\Image;
use comradepashka\gallery\models\ImageAuthor;

class ImageAuthorController extends \yii\web\Controller
{
    public function actionCreate($image_id)
    {
        $image = Image::findOne($image_id);
        $model = new ImageAuthor();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $image->link('imageAuthors', $model);
            return $this->redirect(['index', 'image_id' => $model->image_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'image_id' => $image_id
            ]);
        }
    }
    public function render($view, $params = [])
    {
        return Yii::$app->request->isAjax ?
            parent::renderAjax($view, $params) :
            parent::render($view, $params);
    }

    public function actionDelete($image_id, $user_id)
    {
        $model = ImageAuthor::find()->where(['user_id' => $user_id, 'image_id' => $image_id])->one();
        $model->delete();
        return $this->redirect(['index', 'image_id' => $image_id]);
    }

    public function actionIndex($image_id)
    {
        $models = Image::findOne($image_id)->imageAuthors;
        return $this->render('index', [
            'image_id' => $image_id,
            'models' => $models,
        ]);
    }

    public function actionUpdate($image_id, $user_id)
    {
        $image = Image::findOne($image_id);
        $model = ImageAuthor::find()->where(['user_id' => $user_id, 'image_id' => $image_id])->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $image->link('imageAuthors', $model);
            return $this->redirect(['index', 'image_id' => $model->image_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'image_id' => $image_id
            ]);
        }
    }

}
