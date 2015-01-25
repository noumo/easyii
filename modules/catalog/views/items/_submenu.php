<?php
$action = $this->context->action->id;
?>

<ul class="nav nav-tabs">
    <li <?= ($action === 'edit') ? 'class="active"' : '' ?>><a href="/admin/catalog/items/edit/<?= $model->primaryKey ?>"><?= Yii::t('easyii', 'Edit') ?></a></li>
    <li <?= ($action === 'photos') ? 'class="active"' : '' ?>><a href="/admin/catalog/items/photos/<?= $model->primaryKey ?>"><span class="glyphicon glyphicon-camera"></span> <?= Yii::t('easyii', 'Photos') ?></a></li>
</ul>
<br>