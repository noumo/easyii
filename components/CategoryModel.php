<?php
namespace yii\easyii\components;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\easyii\behaviors\CacheFlush;
use yii\easyii\behaviors\SeoBehavior;
use creocoder\nestedsets\NestedSetsBehavior;

/**
 * Base CategoryModel. Shared by categories
 * @package yii\easyii\components
 * @inheritdoc
 */
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
            ['slug', 'default', 'value' => null],
            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_ON]
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
            'cacheflush' => [
                'class' => CacheFlush::className(),
                'key' => [static::tableName().'_tree', static::tableName().'_flat']
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
            if(!$insert && $this->image != $this->oldAttributes['image'] && $this->oldAttributes['image']){
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

    /**
     * @return ActiveQueryNS
     */
    public static function find()
    {
        return new ActiveQueryNS(get_called_class());
    }

    /**
     * Get cached tree structure of category objects
     * @return array
     */
    public static function tree()
    {
        $cache = Yii::$app->cache;
        $key = static::tableName().'_tree';

        $tree = $cache->get($key);
        if(!$tree){
            $tree = static::generateTree();
            $cache->set($key, $tree, 3600);
        }
        return $tree;
    }

    /**
     * Get cached flat array of category objects
     * @return array
     */
    public static function cats()
    {
        $cache = Yii::$app->cache;
        $key = static::tableName().'_flat';

        $flat = $cache->get($key);
        if(!$flat){
            $flat = static::generateFlat();
            $cache->set($key, $flat, 3600);
        }
        return $flat;
    }

    /**
     * Generates tree from categories
     * @return array
     */
    public static function generateTree()
    {
        $collection = static::find()->with('seo')->sort()->asArray()->all();
        $trees = array();
        $l = 0;

        if (count($collection) > 0) {
            // Node Stack. Used to help building the hierarchy
            $stack = array();

            foreach ($collection as $node) {
                $item = $node;
                unset($item['lft'], $item['rgt'], $item['order_num']);
                $item['children'] = array();

                // Number of stack items
                $l = count($stack);

                // Check if we're dealing with different levels
                while($l > 0 && $stack[$l - 1]->depth >= $item['depth']) {
                    array_pop($stack);
                    $l--;
                }

                // Stack is empty (we are inspecting the root)
                if ($l == 0) {
                    // Assigning the root node
                    $i = count($trees);
                    $trees[$i] = (object)$item;
                    $stack[] = & $trees[$i];

                } else {
                    // Add node to parent
                    $item['parent'] = $stack[$l - 1]->category_id;
                    $i = count($stack[$l - 1]->children);
                    $stack[$l - 1]->children[$i] = (object)$item;
                    $stack[] = & $stack[$l - 1]->children[$i];
                }
            }
        }

        return $trees;
    }

    /**
     * Generates flat array of categories
     * @return array
     */
    public static function generateFlat()
    {
        $collection = static::find()->with('seo')->sort()->asArray()->all();
        $flat = [];

        if (count($collection) > 0) {
            $depth = 0;
            $lastId = 0;
            foreach ($collection as $node) {
                $node = (object)$node;
                $id = $node->category_id;
                $node->parent = '';

                if($node->depth > $depth){
                    $node->parent = $flat[$lastId]->category_id;
                    $depth = $node->depth;
                } elseif($node->depth == 0){
                    $depth = 0;
                } else {
                    if ($node->depth == $depth) {
                        $node->parent = $flat[$lastId]->parent;
                    } else {
                        foreach($flat as $temp){
                            if($temp->depth == $node->depth){
                                $node->parent = $temp->parent;
                                $depth = $temp->depth;
                                break;
                            }
                        }
                    }
                }
                $lastId = $id;
                unset($node->lft, $node->rgt);
                $flat[$id] = $node;
            }
        }

        foreach($flat as &$node){
            $node->children = [];
            foreach($flat as $temp){
                if($temp->parent == $node->category_id){
                    $node->children[] = $temp->category_id;
                }
            }
        }

        return $flat;
    }
}