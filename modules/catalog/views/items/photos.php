<?php
use yii\easyii\widgets\Photos;

$this->title = Yii::t('easyii', 'Photos') . ' ' . $model->title;
$moduleSettings = $this->context->module->settings;
?>

<?= $this->render('_menu', ['category' => $model->category]) ?>
<?= $this->render('_submenu', ['model' => $model]) ?>

<?= Photos::widget([
    'model' => $model,
    'maxWidth' => $moduleSettings['photoMaxWidth'],
    'thumbWidth' => $moduleSettings['photoThumbWidth'],
    'thumbHeight' => $moduleSettings['photoThumbHeight'],
    'thumbCrop' => $moduleSettings['photoThumbCrop']
])?>