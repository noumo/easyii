<?php
namespace yii\easyii\modules\page\models;

use Yii;
use yii\behaviors\SluggableBehavior;

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
            [['title', 'text'], 'trim'],
            ['title', 'string', 'max' => 128],
            ['slug', 'match', 'pattern' => self::$slugPattern, 'message' => Yii::t('easyii', 'Slug can contain only 0-9, a-z and "-" characters (max: 128).')],
            ['slug', 'default', 'value' => null],
            ['slug', 'unique', 'when' => function($model){
                return $model->slug && !self::autoSlug();
            }],
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

    public function beforeValidate()
    {
        if(self::autoSlug()){
            $this->attachBehavior('sluggable', [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'ensureUnique' => true
            ]);
        }
        return parent::beforeValidate();
    }

    public static function autoSlug()
    {
        return Yii::$app->getModule('admin')->activeModules['page']->settings['autoSlug'];
    }
}