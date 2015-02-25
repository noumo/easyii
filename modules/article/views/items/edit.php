<?php
$this->title = Yii::t('easyii/article', 'Edit article');
?>
<?= $this->render('_menu', ['category' => $model->category]) ?>

<?= $this->render('_form', ['model' => $model]) ?>
