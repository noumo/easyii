<?php
use yii\helpers\Url;

$action = $this->context->action->id;
$module = $this->context->module->id;
?>
<?php if(IS_ROOT) : ?>
    <ul class="nav nav-tabs">
        <li <?= ($action === 'edit') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/'.$module.'/a/edit', 'id' => $model->primaryKey]) ?>"><?= Yii::t('easyii', 'Edit') ?></a></li>
        <li <?= ($action === 'fields') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/'.$module.'/a/fields', 'id' => $model->primaryKey]) ?>"><span class="glyphicon glyphicon-cog"></span> <?= Yii::t('easyii', 'Fields') ?></a></li>
    </ul>
    <br>
<?php endif;?>