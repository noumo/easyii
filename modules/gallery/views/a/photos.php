<?php
use yii\easyii\widgets\Photos;

$this->title = $model->title;
?>

<?= $this->render('_menu') ?>
<?= $this->render('_submenu', ['model' => $model]) ?>

<?= Photos::widget([
    'module' => 'gallery',
    'item_id' => $model->primaryKey
])?>