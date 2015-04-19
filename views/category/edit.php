<?php
$this->title = Yii::t('easyii', 'Edit category');
?>
<?= $this->render('_menu') ?>

<?php if(!empty($this->params['submenu'])) echo $this->render($this->params['submenu'], ['model' => $model]); ?>
<?= $this->render('_form', ['model' => $model]) ?>