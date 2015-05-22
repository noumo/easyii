<?php
namespace yii\easyii\models;

class Tag extends \yii\easyii\components\ActiveRecord
{
    public static function tableName()
    {
        return 'easyii_tags';
    }

    public function rules()
    {
        return [
            ['name', 'required'],
            ['frequency', 'integer'],
            ['name', 'string', 'max' => 64],
        ];
    }
}