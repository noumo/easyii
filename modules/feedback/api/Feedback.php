<?php
namespace yii\easyii\modules\feedback\api;

use Yii;
use yii\easyii\modules\feedback\models\Feedback as FeedbackModel;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Alert;
use yii\easyii\widgets\ReCaptcha;

class Feedback extends \yii\easyii\components\API
{
    const SENT_VAR = 'feedback_sent';

    private $_defaultFormOptions = [
        'showAlert' => true
    ];

    public function api_form($options = [])
    {
        $model = new FeedbackModel;
        $settings = Yii::$app->getModule('admin')->activeModules['feedback']->settings;
        $options = array_merge($this->_defaultFormOptions, $options);

        ob_start();
        $form = ActiveForm::begin([
            'enableClientValidation' => true,
            'action' => Url::to(['/admin/feedback/send'])
        ]);

        echo Html::hiddenInput('returnUrl', Yii::$app->controller->route);

        if($options['showAlert']) {
            $sent = Yii::$app->request->get(self::SENT_VAR);
            if ($sent == "1") {
                echo Alert::widget(['options' => ['class' => 'alert-success'], 'body' => Yii::t('easyii/feedback/api', 'Feedback sent. We will answer you soon')]);
            } elseif ($sent == "0") {
                echo Alert::widget(['options' => ['class' => 'alert-danger'], 'body' => Yii::t('easyii/guestbook/api', 'An error has occurred')]);
            }
        }

        echo $form->field($model, 'name');
        echo $form->field($model, 'email')->input('email');
        if($settings['enablePhone']) echo $form->field($model, 'phone');
        if($settings['enableTitle']) echo $form->field($model, 'title');

        echo $form->field($model, 'text')->textarea();

        if($settings['enableCaptcha']) echo $form->field($model, 'reCaptcha')->widget(ReCaptcha::className());

        echo Html::submitButton(Yii::t('easyii', 'Send'), ['class' => 'btn btn-primary']);
        ActiveForm::end();

        return ob_get_clean();
    }

    public function api_save($data)
    {
        $model = new FeedbackModel($data);
        if($model->save()){
            return ['result' => 'success'];
        } else{
            return ['result' => 'error', 'error' => $model->getErrors()];
        }
    }
}