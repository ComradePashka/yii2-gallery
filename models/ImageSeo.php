<?php

namespace comradepashka\gallery\models;

use Yii;

/**
 * This is the model class for table "image_seo".
 *
 * @property integer $image_id
 * @property string $lang
 * @property string $title
 * @property string $header
 * @property string $keywords
 * @property string $description
 */
class ImageSeo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image_seo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image_id', 'lang'], 'required'],
            [['image_id'], 'integer'],
            [['lang'], 'string', 'max' => 2],
            [['title', 'header', 'keywords', 'description'], 'string', 'max' => 255],
            [['image_id', 'lang'], 'unique', 'targetAttribute' => ['image_id', 'lang'], 'message' => 'The combination of Image ID and Lang has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'image_id' => 'Image ID',
            'lang' => 'Language',
            'title' => 'Title',
            'header' => 'Header',
            'keywords' => 'Keywords',
            'description' => 'Description',
        ];
    }
}
