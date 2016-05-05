<?php

namespace comradepashka\gallery\models;

use Yii;
use yii\db\ActiveRecord;

use dektrium\user\models\User;


/**
 * This is the model class for table "ImageAuthor".
 *
 * @property integer $image_id
 * @property integer $user_id
 * @property string $notes
 */
class ImageAuthor extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image_author';
    }

    public function rules()
    {
        return [
            [['image_id', 'user_id'], 'required'],
            ['notes', 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
//            'image_id' => Yii::t('i18n', 'ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Image::className(), ['id' => 'image_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
