<?php
namespace yii\easyii\modules\page\models;

use Yii;

class Page extends \yii\easyii\components\ActiveRecord
{
    public static function tableName()
    {
        return 'easyii_pages';
    }

    public function rules()
    {
        return [
            [['title','text'], 'required'],
            [['title', 'text', 'slug'], 'trim'],
            ['title', 'string', 'max' => 256],
            ['slug', 'unique'],
            ['slug',  'match', 'pattern' => '/^[a-zA-Z][\w_-]*$/'],
            ['slug', 'default', 'value' => null]
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('easyii', 'Title'),
            'text' => Yii::t('easyii', 'Text'),
            'slug' => Yii::t('easyii', 'Slug'),
        ];
    }
}