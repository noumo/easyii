<?php
use yii\easyii\models\Setting;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\easyii\widgets\SeoForm;
?>
<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['class' => 'model-form']
]); ?>
<?= $form->field($model, 'title') ?>
<?= $form->field($model, 'text')->widget(\vova07\imperavi\Widget::className(), [
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

<?php if(IS_ROOT) : ?>
    <?= $form->field($model, 'slug') ?>
    <?= SeoForm::widget(['model' => $model]) ?>
<?php endif; ?>

<?= Html::submitButton(Yii::t('easyii','Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>