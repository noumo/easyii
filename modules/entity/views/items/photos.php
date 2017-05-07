<?php
use yii\easyii\widgets\Photos;

$this->title = Yii::t('easyii', 'Photos') . ' ' . $model->title;
?>

<?= $this->render('_menu', ['category' => $model->category]) ?>
<?= $this->render('_submenu', ['model' => $model]) ?>

<?= Photos::widget(['model' => $model])?>