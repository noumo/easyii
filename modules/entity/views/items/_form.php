<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$settings = $this->context->module->settings;
$module = $this->context->module->id;
?>

<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
]); ?>

<?= $form->field($model, 'title') ?>

<?php if(!empty($cats) && count($cats)) : ?>
    <?= $form->field($model, 'category_id')->dropDownList($cats) ?>
<?php endif; ?>

<?= $dataForm ?>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end(); ?>