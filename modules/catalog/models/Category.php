<?php
namespace yii\easyii\modules\catalog\models;

use Yii;
use yii\easyii\behaviors\CacheFlush;
use yii\easyii\behaviors\SortableModel;

class Category extends \yii\easyii\components\ActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ON = 1;
    const CACHE_KEY = 'easyii_catalog_categories';

    public $item_count;

    static $fieldTypes = [
        'string' => 'String',
        'text' => 'Text',
        'boolean' => 'Boolean',
        'select' => 'Select',
        'checkbox' => 'Checkbox'
    ];

    public static function tableName()
    {
        return 'easyii_catalog_categories';
    }

    public static function findWithItemCount()
    {
        return self::find()
               ->select([self::tableName().'.*', 'COUNT('.Item::tableName().'.item_id) as item_count'])
               ->joinWith('items')
               ->groupBy(self::tableName().'.category_id');
    }

    public function rules()
    {
        return [
            ['title', 'required'],
            [['title', 'slug'], 'trim'],
            [['title', 'slug'], 'string', 'max' => 128],
            ['thumb', 'image'],
            ['item_count', 'integer'],
            ['slug',  'match', 'pattern' => '/^[a-zA-Z][\w_-]*$/'],
            ['slug', 'unique'],
            ['slug', 'default', 'value' => null],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('easyii', 'Title'),
            'thumb' => Yii::t('easyii', 'Image'),
            'slug' => Yii::t('easyii', 'Slug'),
            'item_count' => Yii::t('easyii/catalog', 'Items')
        ];
    }

    public function behaviors()
    {
        return [
            CacheFlush::className(),
            SortableModel::className()
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if(!$this->fields || !is_array($this->fields)){
                $this->fields = [];
            }

            $this->fields = json_encode($this->fields);
            return true;
        } else {
            return false;
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->fields = $this->fields !== '' ? json_decode($this->fields) : [];
    }

    public function getItems()
    {
        return $this->hasMany(Item::className(), ['category_id' => 'category_id'])->sort();
    }

    public function afterDelete()
    {
        parent::afterDelete();

        foreach($this->getItems()->all() as $item){
            $item->delete();
        }

        if($this->thumb) {
            @unlink(Yii::getAlias('@webroot') . $this->thumb);
        }
    }
}