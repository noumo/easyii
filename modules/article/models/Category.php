<?php
namespace yii\easyii\modules\article\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\easyii\behaviors\SeoBehavior;
use yii\easyii\behaviors\SortableModel;
use creocoder\nestedsets\NestedSetsBehavior;

class Category extends \yii\easyii\components\NSActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    public $item_count;

    public static function tableName()
    {
        return 'easyii_article_categories';
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
            ['title', 'trim'],
            ['title', 'string', 'max' => 128],
            ['thumb', 'image'],
            ['item_count', 'integer'],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('easyii', 'Slug can contain only 0-9, a-z and "-" characters (max: 128).')],
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
            'thumb' => Yii::t('easyii', 'Image'),
            'slug' => Yii::t('easyii', 'Slug'),
            'item_count' => Yii::t('easyii/article', 'Items')
        ];
    }

    public function behaviors()
    {
        return [
            SortableModel::className(),
            'seoBehavior' => SeoBehavior::className(),
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree'
            ]
        ];
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

    public static function getTree()
    {
        $collection = self::find()->sort()->asArray()->all();
        $trees = array();
        $l = 0;

        if (count($collection) > 0) {
            // Node Stack. Used to help building the hierarchy
            $stack = array();

            foreach ($collection as $node) {
                $item = $node;
                unset($item['lft'], $item['rgt'], $item['status'], $item['order_num']);
                $item['children'] = array();

                // Number of stack items
                $l = count($stack);

                // Check if we're dealing with different levels
                while($l > 0 && $stack[$l - 1]['depth'] >= $item['depth']) {
                    array_pop($stack);
                    $l--;
                }

                // Stack is empty (we are inspecting the root)
                if ($l == 0) {
                    // Assigning the root node
                    $i = count($trees);
                    $trees[$i] = $item;
                    $stack[] = & $trees[$i];

                } else {
                    // Add node to parent
                    $item['parent'] = $stack[$l - 1]['category_id'];
                    $i = count($stack[$l - 1]['children']);
                    $stack[$l - 1]['children'][$i] = $item;
                    $stack[] = & $stack[$l - 1]['children'][$i];
                }
            }
        }

        return $trees;
    }

    public function getItems()
    {
        return $this->hasMany(Item::className(), ['category_id' => 'category_id'])->sort();
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
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

        foreach($this->getItems()->all() as $item){
            $item->delete();
        }

        if($this->thumb) {
            @unlink(Yii::getAlias('@webroot') . $this->thumb);
        }
    }

    public static function autoSlug()
    {
        return Yii::$app->getModule('admin')->activeModules['article']->settings['categoryAutoSlug'];
    }
}