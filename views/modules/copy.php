<?php
use yii\helpers\Html;

$this->title = $model->title;
?>
<?= $this->render('_menu') ?>
<?= $this->render('_submenu', ['model' => $model]) ?>

<?= Html::beginForm() ?>
<?= Html::textInput('Copy[name]') ?>
<?= Html::textInput('Copy[path]') ?>
<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php Html::endForm(); ?>