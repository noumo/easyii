<?php
$this->title = Yii::t('easyii', 'Category fields');
?>
<?= $this->render('_menu') ?>
<?php if($model instanceof \yii\easyii\components\CategoryWithFieldsModel) echo $this->render('_submenu', ['model' => $model]); ?>
<br>
<?= \yii\easyii\widgets\FieldsTable::widget(['model' => $model]) ?>
