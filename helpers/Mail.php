<?php
namespace yii\easyii\helpers;

use Yii;
use yii\easyii\models\Setting;

class Mail
{
    public static function send($toEmail, $subject, $template, $data = [], $options = [])
    {
        if(!filter_var($toEmail, FILTER_VALIDATE_EMAIL) || !$subject || !$template){
            return false;
        }

        $message = Yii::$app->mailer->compose($template, $data)
            ->setTo(Setting::get('admin_email'))
            ->setSubject(trim($subject));

        if(!filter_var(Setting::get('robot_email'), FILTER_VALIDATE_EMAIL)){
            $message->setFrom(Setting::get('robot_email'));
        }

        if(!empty($options['replyTo']) && filter_var($options['replyTo'], FILTER_VALIDATE_EMAIL)){
            $message->setReplyTo($options['replyTo']);
        }

        return $message->send();
    }
}