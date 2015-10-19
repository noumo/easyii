<?php
$this->title = Yii::t('easyii', 'Edit'). ' ' .$model->title;
?>
<?= $this->render('_menu', ['layout' => $model->layout]) ?>
<?= $this->render('_submenu', ['model' => $model]) ?>

<?= $this->render('_form', ['model' => $model, 'dataForm' => $dataForm]) ?>
