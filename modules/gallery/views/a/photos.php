<?php
use yii\easyii\widgets\Photos;

$this->title = $model->title;
?>

<?= $this->render('@easyii/views/category/_menu') ?>

<?= Photos::widget(['model' => $model])?>