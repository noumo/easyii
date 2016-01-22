<?php
$this->title = Yii::t('easyii', 'Edit'). ' ' .$model->title;
?>
<?= $this->render('_menu', ['model' => $model]) ?>
<?= $this->render('_submenu', ['model' => $model]) ?>

<?= $this->render('_form', ['model' => $model, 'dataForm' => $dataForm]) ?>
