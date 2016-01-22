<?php
use yii\helpers\Url;

$action = $this->context->action->id;
$module = $this->context->module->id;
?>

<ul class="nav nav-tabs">
    <li <?= ($action === 'edit') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/'.$module.'/item/edit', 'id' => $model->primaryKey]) ?>"><?= Yii::t('easyii', 'Edit') ?></a></li>
    <li <?= ($action === 'photos') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/'.$module.'/item/photos', 'id' => $model->primaryKey]) ?>"><span class="glyphicon glyphicon-camera"></span> <?= Yii::t('easyii', 'Photos') ?></a></li>
</ul>
<br>