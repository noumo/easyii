<?php
namespace yii\easyii\modules\subscribe\models;

use Yii;
use yii\easyii\components\ActiveRecord;

class History extends ActiveRecord
{
    public static function tableName()
    {
        return 'easyii_subscribe_history';
    }

    public function rules()
    {
        return [
            [['subject', 'body'], 'required'],
            ['subject', 'trim'],
            ['sent', 'number', 'integerOnly' => true],
            ['time', 'default', 'value' => time()],
        ];
    }

    public function attributeLabels()
    {
        return [
            'subject' => Yii::t('easyii/subscribe', 'Subject'),
            'body' => Yii::t('easyii/subscribe', 'Body'),
        ];
    }
}