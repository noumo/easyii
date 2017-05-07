<?php
namespace yii\easyii\modules\entity\models;

use Yii;
use yii\easyii\behaviors\JsonColumns;
use yii\easyii\behaviors\SortableModel;
use yii\easyii\models\Photo;

class Item extends \yii\easyii\components\ActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    public static function tableName()
    {
        return 'easyii_entity_items';
    }

    public function rules()
    {
        return [
            ['title', 'required'],
            ['title', 'trim'],
            ['title', 'string', 'max' => 128],
            [['status', 'category_id'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ON],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('easyii', 'Title'),
            'category_id' => Yii::t('easyii', 'Category'),
        ];
    }

    public function behaviors()
    {
        return [
            SortableModel::className(),
            'jsonColumns' => [
                'class' => JsonColumns::className(),
                'columns' => ['fields', 'data']
            ],
        ];
    }

    public function afterSave($insert, $attributes){
        parent::afterSave($insert, $attributes);

        if($this->category && !empty($this->category->cache)) {
            Yii::$app->cache->delete(Category::getCacheName($this->category_id));
        }
    }

    public function getPhotos()
    {
        return $this->hasMany(Photo::className(), ['item_id' => 'id'])->where(['class' => self::className()])->sort();
    }

    public function getCategory()
    {
        return Category::get($this->category_id);
    }

    public function afterDelete()
    {
        parent::afterDelete();

        if($this->category && !empty($this->category->cache)) {
            Yii::$app->cache->delete(Category::getCacheName($this->category_id));
        }

        foreach($this->getPhotos()->all() as $photo){
            $photo->delete();
        }
    }
}