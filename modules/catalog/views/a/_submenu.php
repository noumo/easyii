<?php
$action = $this->context->action->id;
?>
<?php if(IS_ROOT) : ?>
    <ul class="nav nav-tabs">
        <li <?= ($action === 'edit') ? 'class="active"' : '' ?>><a href="/admin/catalog/a/edit/<?= $model->primaryKey ?>"><?= Yii::t('easyii', 'Edit') ?></a></li>
        <li <?= ($action === 'fields') ? 'class="active"' : '' ?>><a href="/admin/catalog/a/fields/<?= $model->primaryKey ?>"><span class="glyphicon glyphicon-cog"></span> <?= Yii::t('easyii/catalog', 'Fields') ?></a></li>
    </ul>
    <br>
<?php endif;?>