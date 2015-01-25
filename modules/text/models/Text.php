<?php
namespace yii\easyii\modules\text\models;

use Yii;
use yii\easyii\behaviors\CacheFlush;

class Text extends \yii\easyii\components\ActiveRecord
{
    const CACHE_KEY = 'easyii_text';

    public static function tableName()
    {
        return 'easyii_texts';
    }

    public function rules()
    {
        return [
            ['text_id', 'number', 'integerOnly' => true],
            ['text', 'required'],
            [['text', 'slug'], 'trim'],
            ['slug',  'match', 'pattern' => '/^[a-zA-Z][\w_-]*$/'],
            ['slug', 'unique'],
            ['slug', 'default', 'value' => null]
        ];
    }

    public function attributeLabels()
    {
        return [
            'text' => Yii::t('easyii', 'Text'),
            'slug' => Yii::t('easyii', 'Slug'),
        ];
    }

    public function behaviors()
    {
        return [
            CacheFlush::className()
        ];
    }
}