<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php $form = ActiveForm::begin(['action' => Url::to(['/admin/install'])]); ?>
<?= $form->field($model, 'root_password', ['inputOptions' => ['title' => Yii::t('easyii/install','Password to login as root')]]) ?>
<?= $form->field($model, 'admin_email', ['inputOptions' => ['title' => Yii::t('easyii/install','Used as "ReplyTo" in mail messages')]]) ?>
<?= Html::submitButton(Yii::t('easyii/install', 'Install'), ['class' => 'btn btn-lg btn-primary btn-block']) ?>
<?php ActiveForm::end(); ?>