<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('easyii/content', 'Create item');
?>
<?= $this->render('_menu', ['layout' => $layout]) ?>

<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
]); ?>
<?= $form->field($model, 'title') ?>
<?= $form->field($model, 'category_id')->dropDownList($categories) ?>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>
