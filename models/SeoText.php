<?php
namespace yii\easyii\models;

use Yii;
use yii\easyii\validators\EscapeValidator;

class SeoText extends \yii\easyii\components\ActiveRecord
{
    public static function tableName()
    {
        return 'easyii_seotext';
    }

    public function rules()
    {
        return [
            [['title', 'keywords', 'description'], 'trim'],
            [['title', 'keywords', 'description'], 'string', 'max' => 255],
            [['title', 'keywords', 'description'], EscapeValidator::className()],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Seo Title',
            'keywords' => 'Seo Keywords',
            'description' => 'Seo Description',
        ];
    }
}