<?php
namespace yii\easyii\modules\subscribe\api;

use Yii;
use yii\easyii\modules\subscribe\models\Subscriber;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

class Subscribe extends \yii\easyii\components\API
{
    const SENT_VAR = 'subscribe_sent';

    private $_defaultFormOptions = [
        'showAlert' => true
    ];

    public function api_form($options = [])
    {
        $model = new Subscriber;
        $options = array_merge($this->_defaultFormOptions, $options);

        ob_start();
        $sent = Yii::$app->request->get(self::SENT_VAR);
        if($options['showAlert'] && $sent !== null) {
            if ($sent == "1") {
                echo '<span class="subscribe-success">' . Yii::t('easyii/subscribe/api', 'You have successfully subscribed') . '</span>';
            } else {
                echo '<span class="subscribe-error">' . Yii::t('easyii/subscribe/api', 'An error has occurred') . '</span>';
            }
        } else {
            $form = ActiveForm::begin([
                'enableAjaxValidation' => true,
                'action' => Url::to(['/admin/subscribe/send']),
                'layout' => 'inline'
            ]);
            echo Html::hiddenInput('errorUrl', Url::current([self::SENT_VAR => 0]));
            echo Html::hiddenInput('successUrl', Url::current([self::SENT_VAR => 1]));
            echo $form->field($model, 'email')->input('email', ['placeholder' => 'E-mail']);
            echo Html::submitButton(Yii::t('easyii/subscribe/api', 'Subscribe'), ['class' => 'btn btn-primary', 'id' => 'subscriber-send']);

            ActiveForm::end();
        }

        return ob_get_clean();
    }

    public function api_create($email)
    {
        $model = new Subscriber(['email' => $email]);
        if($model->save()){
            return ['result' => 'success', 'error' => false];
        } else {
            return ['result' => 'error', 'error' => $model->getErrors()];
        }
    }
}