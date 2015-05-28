<?php
use webvimark\behaviors\multilanguage\input_widget\MultiLanguageActiveField;
use yii\easyii\widgets\RedactorMultiLanguage\RedactorMultiLanguageInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\easyii\widgets\SeoForm;
?>
<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['class' => 'model-form']
]); ?>
<?= $form->field($model, 'title')->widget(MultiLanguageActiveField::className()) ?>

<?= RedactorMultiLanguageInput::widget($model, 'text', ['options' => [
    'minHeight' => 400,
    'imageUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'page']),
    'fileUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'page']),
    'plugins' => ['fullscreen']
]]); ?>

<?php if(IS_ROOT) : ?>
    <?= $form->field($model, 'slug') ?>
    <?= SeoForm::widget(['model' => $model]) ?>
<?php endif; ?>

<?= Html::submitButton(Yii::t('easyii','Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>