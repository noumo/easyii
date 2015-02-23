<?php
use yii\easyii\widgets\Photos;

$this->title = $model->title;
$moduleSettings = $this->context->module->settings;
?>

<?= $this->render('_menu') ?>
<?= $this->render('_submenu', ['model' => $model]) ?>

<?= Photos::widget([
    'model' => $model,
    'maxWidth' => $moduleSettings['photoMaxWidth'],
    'thumbWidth' => $moduleSettings['photoThumbWidth'],
    'thumbHeight' => $moduleSettings['photoThumbHeight'],
    'thumbCrop' => $moduleSettings['photoThumbCrop']
])?>