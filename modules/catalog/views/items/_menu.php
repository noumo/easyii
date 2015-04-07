<?php
use yii\helpers\Url;

$action = $this->context->action->id;
?>
<ul class="nav nav-pills">
    <?php if($action === 'index') : ?>
        <li><a href="<?= Url::to(['/admin/catalog']) ?>"><i class="glyphicon glyphicon-chevron-left font-12"></i> <?= Yii::t('easyii/catalog', 'Categories') ?></a></li>
    <?php endif; ?>
    <li <?= ($action === 'index') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/catalog/items', 'id' => $category->primaryKey]) ?>"><?php if($action !== 'index') echo '<i class="glyphicon glyphicon-chevron-left font-12"></i> ' ?><?= $category->title ?></a></li>
    <li <?= ($action === 'create') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/catalog/items/create', 'id' => $category->primaryKey]) ?>"><?= Yii::t('easyii', 'Add') ?></a></li>
</ul>
<br/>