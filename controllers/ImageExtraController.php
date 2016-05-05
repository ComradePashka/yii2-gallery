<?php

namespace comradepashka\gallery\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

use comradepashka\ajaxable\AjaxableBehavior;
use comradepashka\gallery\models\Image;
use comradepashka\gallery\models\ImageExtra;

/**
 * ImageExtraController implements the CRUD actions for ImageExtra model.
 */
class ImageExtraController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'ajaxable' => [
                'class' => AjaxableBehavior::className(),
            ],
        ];
    }

    /**
     * Lists all ImageExtra models.
     * @return mixed
     */
    public function actionIndex($image_id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ImageExtra::find()->where(['image_id' => $image_id]),
        ]);

        return $this->render('index', [
            'image_id' => $image_id,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new ImageExtra model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($image_id)
    {
        $image = Image::findOne($image_id);
        $model = new ImageExtra();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $image->link('imageExtra', $model);
            return $this->redirect(['index', 'image_id' => $model->image_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'image_id' => $image_id
            ]);
        }
    }

    /**
     * Updates an existing ImageExtra model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'image_id' => $model->image_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'image_id' => $model->image_id
            ]);
        }
    }

    public function actionAutocomplete($term)
    {
        yii::$app->response->format = Response::FORMAT_JSON;
        return ImageExtra::find()
            ->select(['value as value', 'value as label'])
            ->where(['like', 'value', $term])
            ->distinct()
            ->asArray()
            ->all();
    }

    /**
     * Deletes an existing ImageExtra model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $image_id = $this->findModel($id)->image_id;
        $this->findModel($id)->delete();

        return $this->redirect(['index', 'image_id' => $image_id]);
    }

    /**
     * Finds the ImageExtra model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ImageExtra the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ImageExtra::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
