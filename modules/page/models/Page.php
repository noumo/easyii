<?php
namespace yii\easyii\modules\page\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\easyii\behaviors\CacheFlush;
use yii\easyii\behaviors\JsonColumns;
use yii\easyii\behaviors\SeoBehavior;
use yii\easyii\components\CategoryWithFieldsModel;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\easyii\components\Module;

class Page extends CategoryWithFieldsModel
{
    public static function tableName()
    {
        return 'easyii_pages';
    }

    public function rules()
    {
        return [
            ['title', 'required'],
            [['title', 'text'], 'trim'],
            ['title', 'string', 'max' => 128],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('easyii', 'Slug can contain only 0-9, a-z and "-" characters (max: 128).')],
            ['slug', 'default', 'value' => null],
            [['status', 'depth', 'tree', 'lft', 'rgt'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ON],
            [['fields', 'data'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('easyii', 'Title'),
            'text' => Yii::t('easyii', 'Text'),
            'slug' => Yii::t('easyii', 'Slug'),
        ];
    }

    public function behaviors()
    {
        $moduleSettings = Yii::$app->getModule('admin')->activeModules[Module::getModuleName(static::className())]->settings;
        return [
            'cacheflush' => [
                'class' => CacheFlush::className(),
                'key' => [static::tableName().'_tree', static::tableName().'_flat']
            ],
            'seoBehavior' => SeoBehavior::className(),
            'sluggable' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'ensureUnique' => true,
                'immutable' => !empty($moduleSettings['slugImmutable']) ? $moduleSettings['slugImmutable'] : false
            ],
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree'
            ],
            'jsonColumns' => [
                'class' => JsonColumns::className(),
                'columns' => ['fields', 'data']
            ],
        ];
    }
}