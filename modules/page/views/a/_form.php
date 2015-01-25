<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\easyii\widgets\Redactor;
?>
<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['enctype' => 'multipart/form-data']
]); ?>
<?= $form->field($model, 'title') ?>
<?php if(IS_ROOT) : ?>
    <?= $form->field($model, 'slug') ?>
<?php endif; ?>
<?= $form->field($model, 'text')->widget(Redactor::className(),[
    'options' => [
        'minHeight' => 400,
        'imageUpload' => '/admin/redactor/upload?dir=pages',
        'fileUpload' => '/admin/redactor/upload?dir=pages',
        'plugins' => ['fullscreen']
    ]
]) ?>
<?= Html::submitButton(Yii::t('easyii','Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>