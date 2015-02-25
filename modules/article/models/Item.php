<?php
namespace yii\easyii\modules\article\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\easyii\behaviors\SeoBehavior;
use yii\easyii\behaviors\SortableModel;
use yii\helpers\StringHelper;

class Item extends \yii\easyii\components\ActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    public static function tableName()
    {
        return 'easyii_article_items';
    }

    public function rules()
    {
        return [
            [['text', 'title'], 'required'],
            [['title', 'short', 'text'], 'trim'],
            ['title', 'string', 'max' => 128],
            ['thumb', 'image'],
            ['views', 'number', 'integerOnly' => true],
            ['slug', 'match', 'pattern' => self::$slugPattern, 'message' => Yii::t('easyii', 'Slug can contain only 0-9, a-z and "-" characters (max: 128).')],
            ['slug', 'default', 'value' => null],
            ['slug', 'unique', 'when' => function($model){
                return $model->slug && !self::autoSlug();
            }]
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('easyii', 'Title'),
            'text' => Yii::t('easyii', 'Text'),
            'short' => Yii::t('easyii/article', 'Short'),
            'thumb' => Yii::t('easyii', 'Image'),
            'slug' => Yii::t('easyii', 'Slug'),
        ];
    }

    public function behaviors()
    {
        return [
            SortableModel::className(),
            'seo' => SeoBehavior::className(),
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'ensureUnique' => true
            ]
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['category_id' => 'category_id']);
    }

    public function beforeValidate()
    {
        if(self::autoSlug() && (!$this->isNewRecord || ($this->isNewRecord && $this->slug == ''))){
            $this->attachBehavior('sluggable', [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'ensureUnique' => true
            ]);
        }
        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $settings = Yii::$app->getModule('admin')->activeModules['article']->settings;
            if($this->short && $settings['enableShort']){
                $this->short = StringHelper::truncate($this->short, $settings['shortMaxLength']);
            }

            if(!$this->isNewRecord && $this->thumb != $this->oldAttributes['thumb']){
                @unlink(Yii::getAlias('@webroot').$this->oldAttributes['thumb']);
            }

            return true;
        } else {
            return false;
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();

        if($this->thumb){
            @unlink(Yii::getAlias('@webroot').$this->thumb);
        }
    }

    public static function autoSlug()
    {
        return Yii::$app->getModule('admin')->activeModules['article']->settings['itemAutoSlug'];
    }
}