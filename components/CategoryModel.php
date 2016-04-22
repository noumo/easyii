<?php
namespace yii\easyii\components;

use Yii;
use yii\easyii\behaviors\CacheFlush;
use yii\easyii\behaviors\ImageFile;
use yii\easyii\behaviors\SeoBehavior;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\easyii\behaviors\SortableModel;
use yii\easyii\behaviors\SlugBehavior;
use yii\easyii\behaviors\Taggable;
use yii\easyii\models\SeoText;
use yii\web\NotFoundHttpException;

/**
 * Base CategoryModel. Shared by categories
 * @package yii\easyii\components
 * @inheritdoc
 */
class CategoryModel extends \yii\easyii\components\ActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    static $FLAT = [];
    static $TREE = [];
    static $RELATIONS = ['seo'];

    public $parent;
    public $children = [];

    public function rules()
    {
        return [
            ['title', 'required'],
            ['title', 'trim'],
            [['title', 'slug'], 'string', 'max' => 128],
            ['description', 'string', 'max' => 1024],
            ['image_file', 'image'],
            ['slug', 'match', 'pattern' => static::$SLUG_PATTERN, 'message' => Yii::t('easyii', 'Slug can contain only 0-9, a-z and "-" characters (max: 128).')],
            ['slug', 'default', 'value' => null],
            ['tagNames', 'safe'],
            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_ON]
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('easyii', 'Title'),
            'description' => Yii::t('easyii', 'Description'),
            'image_file' => Yii::t('easyii', 'Image'),
            'slug' => Yii::t('easyii', 'Slug'),
            'tagNames' => Yii::t('easyii', 'Tags'),
        ];
    }

    public function behaviors()
    {
        $moduleSettings = Yii::$app->getModule('admin')->activeModules[Module::getModuleName(static::className())]->settings;
        $behaviors = [
            'cacheflush' => [
                'class' => CacheFlush::className(),
                'key' => [static::tableName().'_tree', static::tableName().'_flat']
            ],
            'seoBehavior' => SeoBehavior::className(),
            'taggabble' => Taggable::className(),
            'sluggable' => [
                'class' => SlugBehavior::className(),
                'immutable' => !empty($moduleSettings['categorySlugImmutable']) ? $moduleSettings['categorySlugImmutable'] : false
            ],
            'nestedSets' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree'
            ],
        ];

        if(isset($moduleSettings['categoryThumb']) && $moduleSettings['categoryThumb']){
            $behaviors['imageFileBehavior'] = ImageFile::className();
        }

        return $behaviors;
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

        if(empty(static::$FLAT[$key])) {

            $flat = $cache->get($key);
            if (!$flat) {
                $flat = static::generateFlat();
                $cache->set($key, $flat, 3600);
            }
            if(count($flat)) {
                foreach ($flat as $id => $cat) {
                    $model = new static([
                        'id' => $id,
                        'parent' => $cat->parent,
                        'depth' => $cat->depth,
                        'children' => $cat->children
                    ]);

                    $model->load((array)$cat, '');
                    if(in_array('seo', static::$RELATIONS)) {
                        $model->populateRelation('seo', new SeoText($cat->seo));
                    }
                    if(in_array('tags', static::$RELATIONS)) {
                        $model->setTagNames($cat->tags);
                    }
                    $model->afterFind();
                    static::$FLAT[$key][] = $model;
                }
            } else {
                static::$FLAT[$key] = [];
            }
        }
        return static::$FLAT[$key];
    }

    public static function get($id_slug)
    {
        foreach(static::cats() as $cat){
            if($cat->id == $id_slug || $cat->slug == $id_slug){
                return $cat;
            }
        }
        throw new NotFoundHttpException(Yii::t('easyii', 'Category not found'));
    }

    /**
     * Generates tree from categories
     * @return array
     */
    public static function generateTree()
    {
        $collection = static::find()->with(static::$RELATIONS)->sort()->asArray()->all();
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
                    $item['parent'] = $stack[$l - 1]->id;
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
        $collection = static::find()->with(static::$RELATIONS)->sort()->asArray()->all();
        $flat = [];

        if (count($collection) > 0) {
            $depth = 0;
            $lastId = 0;
            foreach ($collection as $node) {
                $node = (object)$node;
                $id = $node->id;
                $node->parent = '';

                if($node->depth > $depth){
                    $node->parent = $flat[$lastId]->id;
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
                if($temp->parent == $node->id){
                    $node->children[] = $temp->id;
                }
            }
            if(!empty($node->tags) && is_array($node->tags) && count($node->tags)){
                $tags = [];
                foreach($node->tags as $tag){
                    $tags[] = $tag['name'];
                }
                $node->tags = $tags;
            }
        }

        return $flat;
    }

    public function create($parent_id = null)
    {
        if ($parent_id && ($parentCategory = static::findOne($parent_id))) {
            $this->order_num = $parentCategory->order_num;
            $this->appendTo($parentCategory);
        } else {
            $this->attachBehavior('sortable', SortableModel::className());
            $this->makeRoot();
        }

        return $this->hasErrors() ? false : true;
    }
}