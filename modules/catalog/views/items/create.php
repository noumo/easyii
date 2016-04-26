<?php
$this->title = Yii::t('easyii', 'Create item');
?>
<?= $this->render('_menu', ['category' => $category]) ?>
<?= $this->render('_form', ['model' => $model, 'dataForm' => $dataForm, 'cats' => $cats]) ?>