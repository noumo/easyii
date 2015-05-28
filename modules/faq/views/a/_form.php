<?php
use yii\easyii\widgets\RedactorMultiLanguage\RedactorMultiLanguageInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin([
    'options' => ['class' => 'model-form']
]); ?>

<?= RedactorMultiLanguageInput::widget($model, 'question', ['options' => [
    'minHeight' => 300,
    'buttons' => ['bold', 'italic', 'unorderedlist', 'link'],
    'linebreaks' => true
]]); ?>

<?= RedactorMultiLanguageInput::widget($model, 'answer', ['options' => [
    'minHeight' => 300,
    'buttons' => ['bold', 'italic', 'unorderedlist', 'link'],
    'linebreaks' => true
]]); ?>

<?= Html::submitButton(Yii::t('easyii','Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>