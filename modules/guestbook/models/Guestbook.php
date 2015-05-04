<?php
namespace yii\easyii\modules\guestbook\models;

use Yii;
use yii\easyii\behaviors\CalculateNotice;
use yii\easyii\models\Setting;
use yii\easyii\validators\ReCaptchaValidator;
use yii\easyii\validators\EscapeValidator;
use yii\helpers\Url;

class Guestbook extends \yii\easyii\components\ActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ON = 1;
    const FLASH_KEY = 'eaysiicms_guestbook_send_result';

    public $reCaptcha;

    public static function tableName()
    {
        return 'easyii_guestbook';
    }

    public function rules()
    {
        return [
            [['name', 'text'], 'required'],
            [['name', 'title', 'text'], 'trim'],
            [['name', 'title', 'text'], EscapeValidator::className()],
            ['email', 'email'],
            ['title', 'string', 'max' => 128],
            ['reCaptcha', ReCaptchaValidator::className(), 'when' => function($model){
                return $model->isNewRecord && Yii::$app->getModule('admin')->activeModules['guestbook']->settings['enableCaptcha'];
            }],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($insert){
                $this->ip = Yii::$app->request->userIP;
                $this->time = time();
                $this->new = 1;
                $this->status = Yii::$app->getModule('admin')->activeModules['guestbook']->settings['preModerate'] ? self::STATUS_OFF : self::STATUS_ON;
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
            'name' => Yii::t('easyii', 'Name'),
            'title' => Yii::t('easyii', 'Title'),
            'email' => 'E-mail',
            'text' => Yii::t('easyii', 'Text'),
            'answer' => Yii::t('easyii/guestbook', 'Answer'),
            'reCaptcha' => Yii::t('easyii', 'Anti-spam check')
        ];
    }

    public function behaviors()
    {
        return [
            'cn' => [
                'class' => CalculateNotice::className(),
                'callback' => function(){
                    return self::find()->where(['new' => 1])->count();
                }
            ]
        ];
    }

    /**
     *
     */
    public function mailAdmin()
    {
        $settings = Yii::$app->getModule('admin')->activeModules['guestbook']->settings;
        $notify = $settings['mailAdminOnNewPost'];
        $template = $settings['templateOnNewPost'];
        $subject = $settings['subjectOnNewPost'];

        if($notify && $template && $subject)
        {
            $data = array_merge($this->attributes, ['link' => Url::to(['/admin/guestbook/a/view', 'id' => $this->primaryKey])]);
            Yii::$app->mailer->compose($template, $data)
                ->setFrom(Setting::get('robot_email'))
                ->setTo(Setting::get('admin_email'))
                ->setSubject($subject)
                ->send();
        }
    }

    public function notifyUser()
    {
        $settings = Yii::$app->getModule('admin')->activeModules['guestbook']->settings;
        $template = $settings['templateNotifyUser'];
        $subject = $settings['subjectNotifyUser'];

        if($template && $subject)
        {
            $data = array_merge($this->attributes, ['link' => Url::to(['/guestbook'])]);
            Yii::$app->mailer->compose($template, $data)
                ->setFrom(Setting::get('robot_email'))
                ->setTo(Setting::get('admin_email'))
                ->setSubject($subject)
                ->send();
        }
    }
}