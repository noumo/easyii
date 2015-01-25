<?php
$action = $this->context->action->id;
?>
<?php if(IS_ROOT) : ?>
<ul class="nav nav-pills">
    <li <?= ($action === 'index') ? 'class="active"' : '' ?>>
        <a href="<?= $this->context->getReturnUrl('/admin/settings') ?>">
            <?php if($action === 'edit') : ?>
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?php endif; ?>
            <?= Yii::t('easyii', 'List') ?>
        </a>
    </li>
    <li <?= ($action==='create') ? 'class="active"' : '' ?>><a href="/admin/settings/create"><?= Yii::t('easyii', 'Create') ?></a></li>
    <li class="pull-right"><a class="text-warning" href="/admin/settings/flushcache"><span class="glyphicon glyphicon-flash"></span> <?= Yii::t('easyii', 'Flush cache') ?></a></li>
</ul>
<br/>
<?php endif; ?>