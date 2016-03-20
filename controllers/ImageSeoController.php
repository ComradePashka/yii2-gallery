<?php

namespace comradepashka\gallery\controllers;

use Yii;
use comradepashka\gallery\models\ImageSeo;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ImageSeoController implements the CRUD actions for ImageSeo model.
 */
class ImageSeoController extends Controller
{
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

    /**
     * Lists all ImageSeo models.
     * @return mixed
     */
    public function actionIndex($image_id = null)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => (($image_id != null) ? ImageSeo::find()->andWhere(['image_id' => $image_id]) : ImageSeo::find(0)),
        ]);
        return $this->render('index', ['dataProvider' => $dataProvider, 'image_id' => $image_id]);
//        return Yii::$app->request->isAjax ?
//            $this->renderAjax('index', ['dataProvider' => $dataProvider]) :
//            $this->render('index', ['dataProvider' => $dataProvider]);
    }

    /**
     * Displays a single ImageSeo model.
     * @param integer $image_id
     * @param string $lang
     * @return mixed
     */
    public function actionView($image_id, $lang)
    {
        return $this->render('view', [
            'model' => $this->findModel($image_id, $lang),
        ]);
    }

    /**
     * Creates a new ImageSeo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($image_id = null)
    {
        $model = new ImageSeo();
        if ($image_id) {
            $model->image_id = $image_id;
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'image_id' => $model->image_id, 'lang' => $model->lang]);
        } else {
            return $this->render('create', ['model' => $model]);
//            return Yii::$app->request->isAjax ? $this->renderAjax('create', ['model' => $model]):$this->render('create', ['model' => $model]);
        }
    }

    /**
     * Updates an existing ImageSeo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $image_id
     * @param string $lang
     * @return mixed
     */
    public function actionUpdate($image_id, $lang)
    {
        $model = $this->findModel($image_id, $lang);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'image_id' => $model->image_id, 'lang' => $model->lang]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ImageSeo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $image_id
     * @param string $lang
     * @return mixed
     */
    public function actionDelete($image_id, $lang)
    {
        $this->findModel($image_id, $lang)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ImageSeo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $image_id
     * @param string $lang
     * @return ImageSeo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($image_id, $lang)
    {
        if (($model = ImageSeo::findOne(['image_id' => $image_id, 'lang' => $lang])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
