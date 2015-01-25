<?php
$action = $this->context->action->id;
?>
<ul class="nav nav-pills">
    <li <?= ($action === 'index') ? 'class="active"' : '' ?>>
        <a href="<?= $this->context->getReturnUrl('/admin/gallery') ?>">
            <?php if($action === 'edit' || $action === 'photos') : ?>
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?php endif; ?>
            <?= Yii::t('easyii/gallery', 'Albums') ?>
        </a>
    </li>
    <li <?= ($action === 'create') ? 'class="active"' : '' ?>><a href="/admin/gallery/a/create"><?= Yii::t('easyii', 'Create') ?></a></li>
</ul>
<br/>