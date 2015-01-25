<?php
$action = $this->context->action->id;
?>
<ul class="nav nav-pills">
    <?php if($action === 'index') : ?>
        <li><a href="/admin/catalog"><i class="glyphicon glyphicon-chevron-left font-12"></i> <?= Yii::t('easyii/catalog', 'Categories') ?></a></li>
    <?php endif; ?>
    <li <?= ($action === 'index') ? 'class="active"' : '' ?>><a href="/admin/catalog/items/<?= $category->primaryKey ?>"><?php if($action !== 'index') echo '<i class="glyphicon glyphicon-chevron-left font-12"></i> ' ?><?= $category->title ?></a></li>
    <li <?= ($action === 'create') ? 'class="active"' : '' ?>><a href="/admin/catalog/items/create/<?= $category->primaryKey ?>"><?= Yii::t('easyii', 'Add') ?></a></li>
</ul>
<br/>