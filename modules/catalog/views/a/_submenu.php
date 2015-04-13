<?php
use yii\helpers\Url;

$action = $this->context->action->id;
?>
<?php if(IS_ROOT) : ?>
    <ul class="nav nav-tabs">
        <li <?= ($action === 'edit') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/catalog/a/edit', 'id' => $model->primaryKey]) ?>"><?= Yii::t('easyii', 'Edit') ?></a></li>
        <li <?= ($action === 'fields') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/catalog/a/fields', 'id' => $model->primaryKey]) ?>"><span class="glyphicon glyphicon-cog"></span> <?= Yii::t('easyii/catalog', 'Fields') ?></a></li>
    </ul>
    <br>
<?php endif;?>