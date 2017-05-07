<?php
use yii\easyii\modules\faq\FaqModule;
use yii\easyii\widgets\TagsInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin([
    'options' => ['class' => 'model-form']
]); ?>
<?php if(FaqModule::setting('questionHtmlEditor')) : ?>
    <?= $form->field($model, 'question')->widget(\yii\easyii\widgets\Redactor::className()) ?>
<?php else : ?>
    <?= $form->field($model, 'question')->textarea(['rows' => 4]) ?>
<?php endif; ?>

<?php if(FaqModule::setting('answerHtmlEditor')) : ?>
    <?= $form->field($model, 'answer')->widget(\yii\easyii\widgets\Redactor::className()) ?>
<?php else : ?>
    <?= $form->field($model, 'answer')->textarea(['rows' => 4]) ?>
<?php endif; ?>

<?php if(FaqModule::setting('enableTags')) : ?>
    <?= $form->field($model, 'tagNames')->widget(TagsInput::className()) ?>
<?php endif; ?>

<?= Html::submitButton(Yii::t('easyii','Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>