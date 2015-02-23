<?php
$action = $this->context->action->id;
?>
<?php if(IS_ROOT) : ?>
<ul class="nav nav-pills">
    <li <?= ($action === 'index') ? 'class="active"' : '' ?>>
        <a href="<?= $this->context->getReturnUrl('/admin/article') ?>">
            <?php if($action === 'fields' || $action === 'edit') : ?>
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?php endif; ?>
            <?= Yii::t('easyii/article', 'Categories') ?>
        </a>
    </li>
    <li <?= ($action === 'create') ? 'class="active"' : '' ?>><a href="/admin/article/a/create"><?= Yii::t('easyii/article', 'Create category') ?></a></li>
</ul>
<br/>
<?php elseif($action === 'edit') : ?>
    <ul class="nav nav-pills">
        <li>
            <a href="<?= $this->context->getReturnUrl('/admin/article')?>">
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
                <?= Yii::t('easyii/article', 'Categories') ?>
            </a>
        </li>
    </ul>
    <br/>
<?php endif; ?>