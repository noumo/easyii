<?php
use yii\helpers\Url;

$action = $this->context->action->id;
$module = $this->context->module->id;

$backTo = null;
$indexUrl = Url::to(['/admin/'.$module]);
$confirmedUrl = Url::to(['/admin/'.$module.'/a/confirmed']);
$sentUrl = Url::to(['/admin/'.$module.'/a/sent']);
$completedUrl = Url::to(['/admin/'.$module.'/a/completed']);
$failsUrl = Url::to(['/admin/'.$module.'/a/fails']);

if($action === 'view')
{
    $returnUrl = $this->context->getReturnUrl($indexUrl);

    if(strpos($returnUrl, 'confirmed') !== false){
        $backTo = 'confirmed';
        $confirmedUrl = $returnUrl;
    } elseif(strpos($returnUrl, 'sent') !== false) {
        $backTo = 'sent';
        $sentUrl = $returnUrl;
    } elseif(strpos($returnUrl, 'completed') !== false) {
        $backTo = 'completed';
        $completedUrl = $returnUrl;
    } elseif(strpos($returnUrl, 'fails') !== false) {
        $backTo = 'fails';
        $failsUrl = $returnUrl;
    } else{
        $backTo = 'index';
        $indexUrl = $returnUrl;
    }
}
?>
<ul class="nav nav-pills">
    <li <?= ($action === 'index') ? 'class="active"' : '' ?>>
        <a href="<?= $indexUrl ?>">
            <?php if($backTo === 'index') : ?>
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?php endif; ?>
            <?= Yii::t('easyii/shopcart', 'Pending') ?>
            <?php if($this->context->pending > 0) : ?>
                <span class="badge"><?= $this->context->pending ?></span>
            <?php endif; ?>
        </a>
    </li>
    <li <?= ($action === 'confirmed') ? 'class="active"' : '' ?>>
        <a href="<?= $confirmedUrl ?>">
            <?php if($backTo === 'confirmed') : ?>
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?php endif; ?>
            <?= Yii::t('easyii/shopcart', 'Confirmed') ?>
            <?php if($this->context->confirmed > 0) : ?>
                <span class="badge"><?= $this->context->confirmed ?></span>
            <?php endif; ?>
        </a>
    </li>
    <li <?= ($action === 'sent') ? 'class="active"' : '' ?>>
        <a href="<?= $sentUrl ?>">
            <?php if($backTo === 'sent') : ?>
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?php endif; ?>
            <?= Yii::t('easyii/shopcart', 'Sent') ?>
            <?php if($this->context->sent > 0) : ?>
                <span class="badge"><?= $this->context->sent ?></span>
            <?php endif; ?>
        </a>
    </li>
    <li <?= ($action === 'completed') ? 'class="active"' : '' ?>>
        <a href="<?= $completedUrl ?>">
            <?php if($backTo === 'completed') : ?>
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?php endif; ?>
            <?= Yii::t('easyii/shopcart', 'Completed') ?>
        </a>
    </li>
    <li <?= ($action === 'fails') ? 'class="active"' : '' ?>>
        <a href="<?= $failsUrl ?>">
            <?php if($backTo === 'fails') : ?>
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?php endif; ?>
            <?= Yii::t('easyii/shopcart', 'Fails') ?>
        </a>
    </li>
</ul>
<br/>