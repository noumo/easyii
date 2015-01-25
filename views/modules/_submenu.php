<?php
$action = $this->context->action->id;
?>

<ul class="nav nav-tabs">
    <li <?= ($action === 'edit') ? 'class="active"' : '' ?>><a href="/admin/modules/edit/<?= $model->primaryKey ?>"> <?= Yii::t('easyii', 'Basic') ?></a></li>
    <li <?= ($action === 'settings') ? 'class="active"' : '' ?>><a href="/admin/modules/settings/<?= $model->primaryKey ?>"><i class="glyphicon glyphicon-cog"></i> <?= Yii::t('easyii', 'Advanced') ?></a></li>
</ul>
<br>