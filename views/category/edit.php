<?php
$this->title = Yii::t('easyii', 'Edit category');
?>
<?= $this->render('_menu') ?>

<?php if($model instanceof \yii\easyii\components\CategoryWithFieldsModel) echo $this->render('_submenu', ['model' => $model]); ?>
<?= $this->render('_form', ['model' => $model]) ?>