<?php
namespace yii\easyii\modules\entity\models;

use Yii;
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
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if(!$this->data || (!is_object($this->data) && !is_array($this->data))){
                $this->data = new \stdClass();
            }
            $this->data = json_encode($this->data);
            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $attributes){
        parent::afterSave($insert, $attributes);
        $this->parseData();
        if($this->category && !empty($this->category->cache)) {
            Yii::$app->cache->delete(Category::getCacheName($this->category_id));
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->parseData();
    }

    public function getPhotos()
    {
        return $this->hasMany(Photo::className(), ['item_id' => 'item_id'])->where(['class' => self::className()])->sort();
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

    private function parseData(){
        $this->data = $this->data !== '' ? json_decode($this->data) : [];
    }
}