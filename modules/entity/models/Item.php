<?php
namespace yii\easyii\modules\entity\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\easyii\behaviors\ImageFile;
use yii\easyii\behaviors\SeoBehavior;
use yii\easyii\models\Photo;
use yii\easyii\modules\entity\EntityModule;

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
            ['image_file', 'image'],
            ['description', 'safe'],
            ['price', 'number'],
            ['discount', 'integer', 'max' => 99],
            [['status', 'category_id', 'available', 'time'], 'integer'],
            ['time', 'default', 'value' => time()],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('easyii', 'Slug can contain only 0-9, a-z and "-" characters (max: 128).')],
            ['slug', 'default', 'value' => null],
            ['status', 'default', 'value' => self::STATUS_ON],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('easyii', 'Title'),
            'image' => Yii::t('easyii', 'Image'),
            'description' => Yii::t('easyii', 'Description'),
            'available' => Yii::t('easyii/entity', 'Available'),
            'price' => Yii::t('easyii/entity', 'Price'),
            'discount' => Yii::t('easyii/entity', 'Discount'),
            'time' => Yii::t('easyii', 'Date'),
            'slug' => Yii::t('easyii', 'Slug'),
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
        return $this->hasOne(Category::className(), ['category_id' => 'category_id']);
    }

    public function afterDelete()
    {
        parent::afterDelete();

        foreach($this->getPhotos()->all() as $photo){
            $photo->delete();
        }
    }

    private function parseData(){
        $this->data = $this->data !== '' ? json_decode($this->data) : [];
    }
}