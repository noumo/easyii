<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin([
    'enableClientValidation' => true
]); ?>
<?= $form->field($model, 'subject') ?>
<?= $form->field($model, 'body')->widget(\yii\easyii\widgets\Redactor::className()) ?>
<?= Html::submitButton(Yii::t('easyii', 'Send'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>