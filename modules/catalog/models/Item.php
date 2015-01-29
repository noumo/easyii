<?php
namespace yii\easyii\modules\catalog\models;

use Yii;
use yii\easyii\behaviors\SortableModel;
use yii\easyii\models\Photo;

class Item extends \yii\easyii\components\ActiveRecord
{

    public static function tableName()
    {
        return 'easyii_catalog_items';
    }

    public function rules()
    {
        return [
            ['title', 'required'],
            [['title', 'slug'], 'trim'],
            ['title', 'string', 'max' => 256],
            ['thumb', 'image'],
            ['slug',  'match', 'pattern' => '/^[a-zA-Z][\w_-]*$/'],
            ['slug', 'unique'],
            ['slug', 'default', 'value' => null],
            ['description', 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('easyii/catalog', 'Title'),
            'thumb' => Yii::t('easyii', 'Image'),
            'description' => Yii::t('easyii', 'Description'),
            'slug' => Yii::t('easyii', 'Slug'),
        ];
    }

    public function behaviors()
    {
        return [
            SortableModel::className()
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

    public function afterFind()
    {
        parent::afterFind();
        $this->data = $this->data !== '' ? json_decode($this->data) : [];
    }

    public function getPhotos()
    {
        return $this->hasMany(Photo::className(), ['item_id' => 'item_id'])->where(['module' => 'catalog'])->sort();
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

        if($this->thumb) {
            @unlink(Yii::getAlias('@webroot') . $this->thumb);
        }
    }
}