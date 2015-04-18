<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = $model->title;
?>
<?= $this->render('_menu') ?>
<?= $this->render('_submenu', ['model' => $model]) ?>

<?php $form = ActiveForm::begin(['enableAjaxValidation' => true]) ?>
<?= $form->field($formModel, 'name') ?>
<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>