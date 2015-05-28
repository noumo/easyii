<?php
use webvimark\behaviors\multilanguage\input_widget\MultiLanguageActiveField;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin([
    'enableClientValidation' => true,
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
]); ?>
<?php if ($model->image) : ?>
    <img src="<?= $model->image ?>" style="width: 848px">
<?php endif; ?>
<?= $form->field($model, 'image')->fileInput() ?>
<?= $form->field($model, 'link') ?>
<?php if ($this->context->module->settings['enableTitle']) : ?>
    <?= $form->field($model, 'title')->textarea()->widget(MultiLanguageActiveField::className(), ['inputType' => 'textArea']) ?>
<?php endif; ?>
<?php if ($this->context->module->settings['enableText']) : ?>
    <?= $form->field($model, 'text')->textarea()->widget(MultiLanguageActiveField::className(), ['inputType' => 'textArea']) ?>
<?php endif; ?>
<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>