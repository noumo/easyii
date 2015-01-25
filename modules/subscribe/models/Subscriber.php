<?php
namespace yii\easyii\modules\subscribe\models;

use Yii;

class Subscriber extends \yii\easyii\components\ActiveRecord
{
    const FLASH_KEY = 'eaysiicms_subscribe_send_result';

    public static function tableName()
    {
        return 'easyii_subscribe_subscribers';
    }

    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'unique'],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($insert){
                $this->ip = Yii::$app->request->userIP;
                $this->time = time();
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
        ];
    }
}