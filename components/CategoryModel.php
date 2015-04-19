<?php
namespace yii\easyii\components;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\easyii\behaviors\CacheFlush;
use yii\easyii\behaviors\SeoBehavior;
use yii\easyii\behaviors\SortableModel;
use yii\easyii\helpers\Data;
use creocoder\nestedsets\NestedSetsBehavior;

class CategoryModel extends \yii\easyii\components\ActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    public function rules()
    {
        return [
            ['title', 'required'],
            ['title', 'trim'],
            ['title', 'string', 'max' => 128],
            ['image', 'image'],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('easyii', 'Slug can contain only 0-9, a-z and "-" characters (max: 128).')],
            ['slug', 'default', 'value' => null]
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('easyii', 'Title'),
            'image' => Yii::t('easyii', 'Image'),
            'slug' => Yii::t('easyii', 'Slug'),
        ];
    }

    public function behaviors()
    {
        return [
            SortableModel::className(),
            'cacheflush' => [
                'class' => CacheFlush::className(),
                'key' => [self::tableName().'_tree', self::tableName().'_flat']
            ],
            'seoBehavior' => SeoBehavior::className(),
            'sluggable' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'ensureUnique' => true
            ],
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree'
            ]
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if(!$this->isNewRecord && $this->image != $this->oldAttributes['image']){
                @unlink(Yii::getAlias('@webroot').$this->oldAttributes['image']);
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();

        if($this->image) {
            @unlink(Yii::getAlias('@webroot') . $this->image);
        }
    }

    public static function find()
    {
        return new ActiveQueryNS(get_called_class());
    }

    public static function tree()
    {
        return Data::cache(self::tableName().'_tree', 3600, function(){
            return self::generateTree();
        });
    }

    public static function flat()
    {
        return Data::cache(self::tableName().'_flat', 3600, function(){
            return self::generateFlat();
        });
    }

    public static function generateTree()
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

    public static function generateFlat()
    {
        $collection = self::find()->sort()->asArray()->all();
        $flat = [];

        if (count($collection) > 0) {
            $depth = 0;
            $lastId = 0;
            foreach ($collection as $node) {
                $id = $node['category_id'];
                $node['parent'] = '';

                if($node['depth'] > $depth){
                    $node['parent'] = $flat[$lastId]['category_id'];
                    $depth = $node['depth'];
                } elseif($node['depth'] == 0){
                    $depth = 0;
                } else {
                    if ($node['depth'] == $depth) {
                        $node['parent'] = $flat[$lastId]['parent'];
                    } else {
                        foreach($flat as $temp){
                            if($temp['depth'] == $node['depth']){
                                $node['parent'] = $temp['parent'];
                                $depth = $temp['depth'];
                                break;
                            }
                        }
                    }
                }
                $lastId = $id;
                unset($node['lft'], $node['rgt']);
                $flat[$id] = $node;
            }
        }

        foreach($flat as &$node){
            $node['children'] = [];
            foreach($flat as $temp){
                if($temp['parent'] == $node['category_id']){
                    $node['children'][] = $temp['category_id'];
                }
            }
        }

        return $flat;
    }
}