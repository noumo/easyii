<?php
namespace yii\easyii\modules\faq\models;

use webvimark\behaviors\multilanguage\MultiLanguageBehavior;
use webvimark\behaviors\multilanguage\MultiLanguageTrait;
use Yii;
use yii\easyii\behaviors\CacheFlush;
use yii\easyii\behaviors\SortableModel;
use yii\easyii\components\ActiveRecord;

class Faq extends ActiveRecord
{
    use MultiLanguageTrait;

    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    const CACHE_KEY = 'easyii_faq';

    public static function tableName()
    {
        return 'easyii_faq';
    }

    public function rules()
    {
        return [
            [['question','answer'], 'required'],
            [['question', 'answer'], 'trim'],
            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_ON],
        ];
    }

    public function attributeLabels()
    {
        return [
            'question' => Yii::t('easyii/faq', 'Question'),
            'answer' => Yii::t('easyii/faq', 'Answer'),
        ];
    }

    public function behaviors()
    {
        return [
            CacheFlush::className(),
            SortableModel::className(),
            'mlBehavior' => [
                'class' => MultiLanguageBehavior::className(),
                'mlConfig' => [
                    'db_table' => 'translations_with_string',
                    'attributes' => ['question', 'answer'],
                    'admin_routes' => [
                        'admin/*',
                    ],
                ],
            ],
        ];
    }
}