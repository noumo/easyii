<?php
use yii\helpers\Url;

$action = $this->context->action->id;

$historyUrl = Url::to(['/admin/subscribe/a/history']);
if($action === 'view'){
    $returnUrl = $this->context->getReturnUrl();
    if(strpos($returnUrl, 'history') !== false){
        $historyUrl = $returnUrl;
    }
}
?>
<ul class="nav nav-pills">
    <li <?= ($action === 'index') ? 'class="active"' : '' ?>>
        <a href="<?= Url::to(['/admin/subscribe']) ?>"><?= Yii::t('easyii/subscribe', 'Subscribers') ?></a>
    </li>
    <li <?= ($action === 'create') ? 'class="active"' : '' ?>>
        <a href="<?= Url::to(['/admin/subscribe/a/create']) ?>">
        <?= Yii::t('easyii/subscribe', 'Create subscribe') ?>
        </a>
    </li>
    <li <?= ($action === 'history') ? 'class="active"' : '' ?>>
        <a href="<?= $historyUrl ?>">
            <?php if($action === 'view') : ?>
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?php endif; ?>
            <?= Yii::t('easyii/subscribe', 'History') ?>
        </a>
    </li>
</ul>
<br/>
