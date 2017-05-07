<?php
$this->title = Yii::t('easyii', 'Fields');
?>
<?= $this->render('_menu') ?>
<?= $this->render('_submenu', ['model' => $model]); ?>
<br>
<?= \yii\easyii\widgets\FieldsTable::widget(['model' => $model]) ?>