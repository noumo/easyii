<?php

namespace yii\easyii\modules\feedback\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\easyii\behaviors\CalculateNotice;
use yii\easyii\helpers\Mail;
use yii\easyii\models\Setting;
use yii\easyii\validators\ReCaptchaValidator;
use yii\easyii\validators\EscapeValidator;
use yii\helpers\Url;

/**
 * Class Feedback
 * @package yii\easyii\modules\feedback\models
 *
 * @property int $status
 * @property int $time
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $title
 * @property string $text
 * @property string $answer_text
 */
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

    public function init()
    {
        $this->status = self::STATUS_NEW;
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
            ['reCaptcha', ReCaptchaValidator::className(), 'when' => function ($model) {
                return $model->isNewRecord && Yii::$app->getModule('admin')->activeModules['feedback']->settings['enableCaptcha'];
            }],
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
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
                'callback' => function () {
                    return self::find()->status(self::STATUS_NEW)->count();
                }
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['ip'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['ip'],
                ],
                'value' => Yii::$app->request->userIP,
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'time',
                'updatedAtAttribute' => false,
            ],
        ];
    }

    public function mailAdmin()
    {
        $settings = Yii::$app->getModule('admin')->activeModules['feedback']->settings;

        if (!$settings['mailAdminOnNewFeedback']) {
            return false;
        }
        return Mail::send(
            Setting::get('admin_email'),
            $settings['subjectOnNewFeedback'],
            $settings['templateOnNewFeedback'],
            [
                'feedback' => $this->getAttributes(),
                'link' => Url::to(['/admin/feedback/a/view','id' => $this->primaryKey], true),
                'nice_date' => Yii::$app->formatter->asDatetime($this->time, 'medium'),
                'html_text' => nl2br($this->text),
            ]
        );
    }

    public function sendAnswer()
    {
        $settings = Yii::$app->getModule('admin')->activeModules['feedback']->settings;

        return Mail::send(
            $this->email,
            $this->answer_subject,
            $settings['answerTemplate'],
            [
                'feedback' => $this->getAttributes(),
                'nice_date' => Yii::$app->formatter->asDatetime($this->time, 'medium'),
                'html_answer' => nl2br($this->answer_text),
                'html_text' => nl2br($this->text),
            ],
            ['replyTo' => Setting::get('admin_email')]
        );
    }
}