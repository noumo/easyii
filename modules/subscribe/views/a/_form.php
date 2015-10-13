<?php
use yii\easyii\models\Setting;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin([
    'enableClientValidation' => true
]); ?>
<?= $form->field($model, 'subject') ?>
<?= $form->field($model, 'body')->widget(\vova07\imperavi\Widget::className(), [
    'settings' => [
        'lang' => \yii\easyii\helpers\Data::getLocale(),
        'minHeight' => 400,
        'imageUpload' => Url::to(['/admin/redactor/image-upload']),
        'fileUpload' => Url::to(['/admin/redactor/file-upload']),
        'imageManagerJson' => Url::to(['/admin/redactor/images-get']),
        'fileManagerJson' => Url::to(['/admin/redactor/files-get']),
        'plugins' => Setting::getAsArray('redactor_plugins')
    ]
]) ?>
<?= Html::submitButton(Yii::t('easyii', 'Send'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>