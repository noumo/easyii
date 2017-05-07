<?php
namespace yii\easyii\modules\feedback\models;

use Yii;
use yii\easyii\behaviors\CalculateNotice;
use yii\easyii\helpers\Mail;
use yii\easyii\models\Setting;
use yii\easyii\modules\feedback\FeedbackModule;
use yii\easyii\validators\ReCaptchaValidator;
use yii\easyii\validators\EscapeValidator;
use yii\helpers\Url;

class Feedback extends \yii\easyii\components\ActiveRecord
{
    const STATUS_NEW = 0;
    const STATUS_VIEW = 1;
    const STATUS_ANSWERED = 2;

    const FLASH_KEY = 'eaysiicms_feedback_send_result';

    public $reCaptcha;

    public static function tableName()
    {
        return 'easyii_feedback';
    }

    public function rules()
    {
        return [
            ['name', 'required'],
            ['email', 'required', 'when' => function($model) { return (!FeedbackModule::setting('enablePhone') || !$model->phone); }],
            ['phone', 'required', 'when' => function($model) { return (!FeedbackModule::setting('enableEmail') || !$model->email); }],
            ['text', 'required', 'when' => function($model) { return FeedbackModule::setting('enableText'); }],
            [['name', 'email', 'phone', 'title', 'text'], 'trim'],
            [['name','title', 'text'], EscapeValidator::className()],
            ['title', 'string', 'max' => 128],
            ['email', 'email'],
            ['phone', 'match', 'pattern' => '/^[\d\s-\+\(\)]+$/'],
            ['reCaptcha', ReCaptchaValidator::className(), 'when' => function($model){
                return $model->isNewRecord && FeedbackModule::setting('enableCaptcha');
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

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if($insert){
            $this->mailAdmin();
        }
    }

    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
            'name' => Yii::t('easyii', 'Name'),
            'title' => Yii::t('easyii', 'Title'),
            'text' => Yii::t('easyii', 'Text'),
            'answer_subject' => Yii::t('easyii/feedback', 'Subject'),
            'answer_text' => Yii::t('easyii', 'Text'),
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

    public function mailAdmin()
    {
        if(!FeedbackModule::setting('mailAdminOnNewFeedback')){
            return false;
        }
        return Mail::send(
            Setting::get('admin_email'),
            FeedbackModule::setting('subjectOnNewFeedback'),
            FeedbackModule::setting('templateOnNewFeedback'),
            ['feedback' => $this, 'link' => Url::to(['/admin/feedback/a/view', 'id' => $this->primaryKey], true)]
        );
    }

    public function sendAnswer()
    {
        return Mail::send(
            $this->email,
            $this->answer_subject,
            FeedbackModule::setting('answerTemplate'),
            ['feedback' => $this],
            ['replyTo' => Setting::get('admin_email')]
        );
    }
}