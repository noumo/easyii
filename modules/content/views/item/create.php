<?php
$this->title = Yii::t('easyii/content', 'Create item');
?>
<?= $this->render('_menu', ['layout' => $layout]) ?>
<?= $this->render('_form', ['model' => $model, 'dataForm' => $dataForm]) ?>