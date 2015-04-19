<?php
$this->title = Yii::t('easyii', 'Create category');
?>
<?= $this->render('_menu') ?>
<?= $this->render('_form', ['model' => $model, 'parent' => $parent]) ?>