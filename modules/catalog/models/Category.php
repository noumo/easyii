<?php
namespace yii\easyii\modules\catalog\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\easyii\behaviors\CacheFlush;
use yii\easyii\behaviors\SeoBehavior;
use yii\easyii\behaviors\SortableModel;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\easyii\helpers\Data;

class Category extends \yii\easyii\components\ActiveRecordNS
{
    const STATUS_OFF = 0;
    const STATUS_ON = 1;
    const TREE_CACHE_KEY = 'easyii_catalog_tree';
    const FLAT_CACHE_KEY = 'easyii_catalog_flat';

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

    public function rules()
    {
        return [
            ['title', 'required'],
            ['title', 'trim'],
            ['title', 'string', 'max' => 128],
            ['image', 'image'],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('easyii', 'Slug can contain only 0-9, a-z and "-" characters (max: 128).')],
            ['slug', 'default', 'value' => null],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('easyii', 'Title'),
            'image' => Yii::t('easyii', 'Image'),
            'slug' => Yii::t('easyii', 'Slug'),
            'item_count' => Yii::t('easyii/catalog', 'Items')
        ];
    }

    public function behaviors()
    {
        return [
            SortableModel::className(),
            'cacheflush' => [
                'class' => CacheFlush::className(),
                'key' => [self::TREE_CACHE_KEY, self::FLAT_CACHE_KEY]
            ],
            'seo' => SeoBehavior::className(),
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree'
            ],
            'sluggable' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'ensureUnique' => true
            ]
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if(!$this->fields || !is_array($this->fields)){
                $this->fields = [];
            }
            $this->fields = json_encode($this->fields);

            if(!$this->isNewRecord && $this->image != $this->oldAttributes['image']){
                @unlink(Yii::getAlias('@webroot').$this->oldAttributes['image']);
            }

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

        if($this->image) {
            @unlink(Yii::getAlias('@webroot') . $this->image);
        }
    }

    public static function tree()
    {
        return Data::cache(self::TREE_CACHE_KEY, 3600, function(){
            return self::getTree();
        });
    }

    public static function flat()
    {
        return Data::cache(self::FLAT_CACHE_KEY, 3600, function(){
            return self::getFlat();
        });
    }
}