<?php
$action = $this->context->action->id;
?>
<ul class="nav nav-pills">
    <?php if($action === 'index') : ?>
        <li><a href="/admin/article"><i class="glyphicon glyphicon-chevron-left font-12"></i> <?= Yii::t('easyii/article', 'Categories') ?></a></li>
    <?php endif; ?>
    <li <?= ($action === 'index') ? 'class="active"' : '' ?>><a href="/admin/article/items/<?= $category->primaryKey ?>"><?php if($action !== 'index') echo '<i class="glyphicon glyphicon-chevron-left font-12"></i> ' ?><?= $category->title ?></a></li>
    <?php if($action === 'index') : ?>
        <a href="/admin/article/items/create/<?= $category->primaryKey ?>" class="btn btn-success pull-right"><i class="glyphicon glyphicon-plus font-12"></i> <?= Yii::t('easyii', 'Add') ?></a>
    <?php endif; ?>
</ul>
<br/>