<?php
$action = $this->context->action->id;
?>

<ul class="nav nav-tabs">
    <li <?= ($action === 'photos') ? 'class="active"' : '' ?>><a href="/admin/gallery/a/photos/<?= $model->primaryKey ?>"><span class="glyphicon glyphicon-camera"></span> <?= Yii::t('easyii', 'Photos') ?></a></li>
    <li <?= ($action === 'edit') ? 'class="active"' : '' ?>><a href="/admin/gallery/a/edit/<?= $model->primaryKey ?>"><?= Yii::t('easyii/gallery', 'Edit album') ?></a></li>
</ul>
<br>