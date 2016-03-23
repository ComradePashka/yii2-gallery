<?php

namespace comradepashka\gallery\models;

use Yii;

/**
 * This is the model class for table "image_translation".
 *
 * @property integer $image_id
 * @property string $language
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property string $header
 */
class ImageTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image_id', 'language', 'title', 'description', 'keywords', 'header'], 'required'],
            [['image_id'], 'integer'],
            [['language'], 'string', 'max' => 2],
            [['title', 'description', 'keywords', 'header'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'image_id' => 'Image ID',
            'language' => 'Language',
            'title' => 'Title',
            'description' => 'Description',
            'keywords' => 'Keywords',
            'header' => 'Header',
        ];
    }
}
