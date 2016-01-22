<?php
namespace yii\easyii\components;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\easyii\behaviors\CacheFlush;
use yii\easyii\behaviors\ImageFile;
use yii\easyii\behaviors\SeoBehavior;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\easyii\behaviors\Taggable;

/**
 * Base CategoryModel. Shared by categories
 *
 * @property string $title
 * @property string $image
 * @property string $slug
 *
 * @package yii\easyii\components
 * @inheritdoc
 */
class CategoryModel extends \yii\easyii\components\ActiveRecord
{
    use TreeTrait;
    use FlatTrait;

    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    public $parent;
    public $children;

    public function rules()
    {
        return [
            ['title', 'required'],
            ['title', 'trim'],
            [['title', 'slug'], 'string', 'max' => 128],
            ['image_file', 'image'],
            ['slug', 'match', 'pattern' => static::$SLUG_PATTERN, 'message' => Yii::t('easyii', 'Slug can contain only 0-9, a-z and "-" characters (max: 128).')],
            ['slug', 'default', 'value' => null],
            ['tagNames', 'safe'],
            [['status', 'depth', 'tree', 'lft', 'rgt'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ON]
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('easyii', 'Title'),
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
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'ensureUnique' => true,
                'immutable' => !empty($moduleSettings['categorySlugImmutable']) ? $moduleSettings['categorySlugImmutable'] : false
            ],
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree'
            ]
        ];

        if($moduleSettings['categoryThumb']){
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

    public static function get($id_slug)
    {
        foreach(static::cats() as $cat){
            if($cat->category_id == $id_slug || $cat->slug == $id_slug){
                return $cat;
            }
        }
        return null;
    }
}