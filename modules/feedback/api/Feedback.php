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
    public function api_form()
    {
        $model = new FeedbackModel;
        $settings = Yii::$app->getModule('admin')->activeModules['feedback']->settings;

        ob_start();
        $form = ActiveForm::begin([
            'enableClientValidation' => true,
            'action' => Url::to(['/admin/feedback/send'])
        ]);

        switch(Yii::$app->session->getFlash(FeedbackModel::FLASH_KEY))
        {
            case 'success' :
                echo Alert::widget(['options' => ['class' => 'alert-success'],'body' => Yii::t('easyii/feedback/api', 'Feedback send. We will answer you soon')]);
                break;
            case 'error' :
                echo Alert::widget(['options' => ['class' => 'alert-danger'],'body' => Yii::t('easyii/feedback/api', 'An error has occurred')]);
                break;
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

    public function api_create($data)
    {
        $model = new FeedbackModel($data);
        if($model->save()){
            return ['result' => 'success'];
        } else{
            return ['result' => 'error', 'error' => $model->getErrors()];
        }
    }
}