<?php
namespace yii\easyii\modules\subscribe\api;

use Yii;
use yii\easyii\modules\subscribe\models\Subscriber;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

class Subscribe extends \yii\easyii\components\API
{
    public $options = [
        'buttonClass' => 'btn btn-primary',
        'buttonText' => 'Subscribe'
    ];

    public function api_form($options = [])
    {
        if(is_array($options) && count($options)) $this->options = array_merge($this->options, $options);

        $model = new Subscriber;

        ob_start();
        $form = ActiveForm::begin([
            'enableAjaxValidation' => true,
            'action' => Url::to(['/admin/subscribe/send']),
            'layout' => 'inline'
        ]);

        switch(Yii::$app->session->getFlash(Subscriber::FLASH_KEY)){
            case 'success' :
                Yii::$app->getView()->registerJs('alert("'.Yii::t('easyii/subscribe/api', 'You have successfully subscribed').'");');
                break;
            case 'error' :
                Yii::$app->getView()->registerJs('alert("'.Yii::t('easyii/subscribe/api', 'An error has occurred').'");');
                break;
        }

        echo $form->field($model, 'email')->input('email', ['placeholder' => 'E-mail']);
        echo Html::submitButton(Yii::t('easyii/subscribe/api', $this->options['buttonText']), ['class' => $this->options['buttonClass'], 'id' => 'subscriber-send']);

        ActiveForm::end();

        return ob_get_clean();
    }

    public function api_create($email)
    {
        $model = new Subscriber(['email' => $email]);
        if($model->save()){
            return ['result' => 'success', 'error' => false];
        } else{
            return ['result' => 'error', 'error' => $model->getErrors()];
        }
    }
}