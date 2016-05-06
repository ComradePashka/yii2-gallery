<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 3/11/2016
 * Time: 1:19 AM
 */

namespace comradepashka\gallery\controllers;

use comradepashka\gallery\models\Image;
use comradepashka\gallery\models\ImageAuthor;
use comradepashka\gallery\models\ImageExtra;
use Yii;
use yii\web\Controller;
use comradepashka\gallery\Module;
use yii\web\Response;

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

    public function actionDeleteAll()
    {
        $images = Module::getImages();
        foreach ($images as $i) {
            $i->delete();
        }
        return $this->redirect(['image/index', 'currentPath' => Module::$currentPath]);
    }

    public function actionCloneMeta($id)
    {
        $images = Module::getImages();
        $ingot = Image::findOne($id);
        Image::$reGenPicture = false;
        foreach ($images as $i) {
            $i->title = $ingot->title;
            $i->description = $ingot->description;
            $i->keywords = $ingot->keywords;
            $i->header = $ingot->header;
            $i->save();
        }
        Image::$reGenPicture = true;
        return $this->redirect(['image/index', 'currentPath' => Module::$currentPath]);
    }
    public function actionCloneExtra($id)
    {
        $images = Module::getImages();
        $ingot = Image::findOne($id);
        Image::$reGenPicture = false;
        foreach ($images as $i) {
            foreach ($ingot->imageExtra as $ie) {
                $extra = new ImageExtra();
                $data = $ie->attributes;
                $data['image_id'] = $i->id;
                unset($data['id']);
                $extra->setAttributes($data, false);
                $i->link('imageExtra', $extra);
            }
            $i->save();
        }
        Image::$reGenPicture = true;
        return $this->redirect(['image/index', 'currentPath' => Module::$currentPath]);
    }

    public function actionCloneTags($id)
    {
        $images = Module::getImages();
        $ingot = Image::findOne($id);
        Image::$reGenPicture = false;
        foreach ($images as $i) {
            $i->tagValues = $ingot->tagValues;
            $i->save();
        }
        Image::$reGenPicture = true;
        return $this->redirect(['image/index', 'currentPath' => Module::$currentPath]);
    }

    public function actionCloneAuthor($id)
    {
        $images = Module::getImages();
        $ingot = Image::findOne($id);
        Image::$reGenPicture = false;

        foreach ($images as $i) {
            foreach ($ingot->imageAuthors as $ia) {
                if (! ($author = ImageAuthor::findOne(['image_id' => $i->id, 'user_id' => $ia->user_id]))) {
                    $author = new ImageAuthor();
                }
                $author->setAttributes($ia->attributes, false);
                $i->link('imageAuthors', $author);
            }
            $i->save();
        }
        Image::$reGenPicture = true;
        return $this->redirect(['image/index', 'currentPath' => Module::$currentPath]);
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

    public function actionAjaxCreateAlbum($name)
    {
        yii::$app->response->format = Response::FORMAT_JSON;
        if (@mkdir(Module::getGallery()->WebRootPath . Module::$currentPath . $name)) {
            chmod(Module::getGallery()->WebRootPath . Module::$currentPath . $name, 0777);
            return ['currentPath' => Module::$currentPath . $name . "/"];
        }
        else {
            return ['error' => "Can not create album: " . $name];
        }
    }

    public function actionAjaxFileList($cwd)
    {
        yii::$app->response->format = Response::FORMAT_JSON;
        $cd = Module::$currentPath;
        Module::$currentPath = $cwd;
        $albums = Module::getAlbums();
        $images = [];
        foreach (Module::getImages() as $image) {
            $images[$image->id] = $image->getWebVersionPath('-tiny');
        }
        Module::$currentPath = $cd;
        return ['albums' => $albums, 'images' => $images];
    }

    public function actionAjaxMergeImage($id, $newImageId) {
        yii::$app->response->format = Response::FORMAT_JSON;
        $image = Image::findOne($id);
        $titilr ="";
        $image->merge($newImageId);
        $image = Image::findOne($newImageId);
        return $image;
    }

    public function actionAjaxImageExtraAutocomplete($item, $term) {
        yii::$app->response->format = Response::FORMAT_JSON;
        if (preg_match("/(value|category)/", $item)) {
            return ImageExtra::find()
                ->select(["$item as value", "$item as label"])
                ->where(['like', $item, $term])
                ->distinct()
                ->asArray()
                ->all();
        } else return [];
    }


    public function actionRefresh()
    {
        Yii::$app->response->headers->set('refresh', '5');
        return "5sec to go!";
    }

}