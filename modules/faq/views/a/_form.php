<?php
use yii\easyii\widgets\TagsInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\easyii\widgets\Redactor;
?>
<?php $form = ActiveForm::begin([
    'options' => ['class' => 'model-form']
]); ?>
<?= $form->field($model, 'question')->widget(Redactor::className(),[
    'options' => [
        'minHeight' => 300,
        'buttons' => ['bold', 'italic', 'unorderedlist', 'link'],
        'linebreaks' => true
    ]
]) ?>
<?= $form->field($model, 'answer')->widget(Redactor::className(),[
    'options' => [
        'minHeight' => 300,
        'buttons' => ['bold', 'italic', 'unorderedlist', 'link'],
        'linebreaks' => true
    ]
]) ?>

<?php if($this->context->module->settings['enableTags']) : ?>
    <?= $form->field($model, 'tagNames')->widget(TagsInput::className()) ?>
<?php endif; ?>

<?= Html::submitButton(Yii::t('easyii','Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>