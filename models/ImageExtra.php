<?php

namespace comradepashka\gallery\models;

use Yii;

/**
 * This is the model class for table "image_extra".
 *
 * @property integer $id
 * @property integer $image_id
 * @property string $category
 * @property string $value
 */
class ImageExtra extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image_extra';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image_id', 'category', 'value'], 'required'],
            [['image_id'], 'integer'],
            [['category', 'value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image_id' => 'Image ID',
            'category' => 'Category',
            'value' => 'Value',
        ];
    }
}
