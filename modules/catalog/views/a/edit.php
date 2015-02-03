<?php
$this->title = Yii::t('easyii/catalog', 'Edit category');
?>
<?= $this->render('_menu') ?>
<?= $this->render('_submenu', ['model' => $model]) ?>
<?= $this->render('_form', ['model' => $model]) ?>
