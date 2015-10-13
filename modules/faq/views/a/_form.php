<?php
use yii\easyii\models\Setting;
use yii\easyii\widgets\TagsInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin([
    'options' => ['class' => 'model-form']
]); ?>
<?= $form->field($model, 'question')->widget(\vova07\imperavi\Widget::className(), [
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
<?= $form->field($model, 'answer')->widget(\vova07\imperavi\Widget::className(), [
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

<?php if($this->context->module->settings['enableTags']) : ?>
    <?= $form->field($model, 'tagNames')->widget(TagsInput::className()) ?>
<?php endif; ?>

<?= Html::submitButton(Yii::t('easyii','Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>