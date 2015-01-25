<?php
$this->title = Yii::t('easyii/page', 'Create page');
?>
<?= $this->render('_menu') ?>
<?= $this->render('_form', ['model' => $model]) ?>