<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\easyii\models\Setting;
?>
<?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
<?php if(IS_ROOT) : ?>
    <?= $form->field($model, 'name')->textInput(!$model->isNewRecord ? ['disabled' => 'disabled'] : []) ?>
    <?= $form->field($model, 'visibility')->checkbox(['uncheck' => Setting::VISIBLE_ALL]) ?>
<?php endif ?>
<?= $form->field($model, 'title')->textarea(['disabled' => !IS_ROOT]) ?>
<?= $form->field($model, 'value')->textarea() ?>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>