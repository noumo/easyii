<?php
$action = $this->context->action->id;
?>
<?php if(IS_ROOT) : ?>
    <ul class="nav nav-pills">
        <li <?= ($action === 'index') ? 'class="active"' : '' ?>>
            <a href="<?= $this->context->getReturnUrl('/admin/text') ?>">
                <?php if($action === 'edit') : ?>
                    <i class="glyphicon glyphicon-chevron-left font-12"></i>
                <?php endif; ?>
                <?= Yii::t('easyii', 'List') ?>
            </a>
        </li>
        <li <?= ($action === 'create') ? 'class="active"' : '' ?>><a href="/admin/text/a/create"><?= Yii::t('easyii', 'Create') ?></a></li>
    </ul>
    <br/>
<?php elseif($action === 'edit') : ?>
    <ul class="nav nav-pills">
        <li>
            <a href="<?= $this->context->getReturnUrl('/admin/text')?>">
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
                <?= Yii::t('easyii/text', 'Texts') ?>
            </a>
        </li>
    </ul>
    <br/>
<?php endif; ?>