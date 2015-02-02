<?php
namespace yii\easyii\modules\feedback\models;

use Yii;
use yii\easyii\behaviors\CalculateNotice;
use yii\easyii\validators\ReCaptchaValidator;
use yii\easyii\validators\EscapeValidator;

class Feedback extends \yii\easyii\components\ActiveRecord
{
    const STATUS_NEW = 0;
    const STATUS_VIEW = 1;
    const STATUS_ANSWER = 2;

    const FLASH_KEY = 'eaysiicms_feedback_send_result';

    public static function tableName()
    {
        return 'easyii_feedback';
    }

    public function rules()
    {
        return [
            [['name', 'email', 'text'], 'required'],
            [['name', 'email', 'phone', 'title', 'text'], 'trim'],
            [['name','title', 'text'], EscapeValidator::className()],
            ['title', 'string', 'max' => 128],
            ['email', 'email'],
            ['phone', 'match', 'pattern' => '/^[\d\s-\+\(\)]+$/'],
            ['reCaptcha', ReCaptchaValidator::className(), 'when' => function($model){
                return $model->isNewRecord && Yii::$app->getModule('admin')->activeModules['feedback']->settings['enableCaptcha'];
            }],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($insert){
                $this->ip = Yii::$app->request->userIP;
                $this->time = time();
                $this->status = self::STATUS_NEW;
            }
            return true;
        } else {
            return false;
        }
    }

    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
            'name' => Yii::t('easyii', 'Name'),
            'title' => Yii::t('easyii', 'Title'),
            'text' => Yii::t('easyii', 'Text'),
            'answer' => Yii::t('easyii/feedback', 'Answer'),
            'phone' => Yii::t('easyii/feedback', 'Phone'),
            'reCaptcha' => Yii::t('easyii', 'Anti-spam check')
        ];
    }

    public function behaviors()
    {
        return [
            'cn' => [
                'class' => CalculateNotice::className(),
                'callback' => function(){
                    return self::find()->status(self::STATUS_NEW)->count();
                }
            ]
        ];
    }
}