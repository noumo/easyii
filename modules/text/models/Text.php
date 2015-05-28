<?php
namespace yii\easyii\modules\text\models;

use webvimark\behaviors\multilanguage\MultiLanguageBehavior;
use webvimark\behaviors\multilanguage\MultiLanguageTrait;
use Yii;
use yii\easyii\behaviors\CacheFlush;
use yii\easyii\components\ActiveRecord;

/**
 * Text module model class
 * @package yii\easyii\modules\text\models
 * @inheritdoc
 */
class Text extends ActiveRecord
{
    use MultiLanguageTrait;

    const CACHE_KEY = 'easyii_text';

    public static function tableName()
    {
        return 'easyii_texts';
    }

    public function rules()
    {
        return [
            ['text_id', 'number', 'integerOnly' => true],
            ['text', 'required'],
            ['text', 'trim'],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('easyii', 'Slug can contain only 0-9, a-z and "-" characters (max: 128).')],
            ['slug', 'default', 'value' => null],
            ['slug', 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'text' => Yii::t('easyii', 'Text'),
            'slug' => Yii::t('easyii', 'Slug'),
        ];
    }

    public function behaviors()
    {
        return [
            CacheFlush::className(),
            'mlBehavior' => [
                'class' => MultiLanguageBehavior::className(),
                'mlConfig' => [
                    'db_table' => 'translations_with_string',
                    'attributes' => ['text'],
                    'admin_routes' => [
                        'admin/text/a/edit',
                        'admin/text/a/create',
                    ],
                ],
            ],
        ];
    }
}