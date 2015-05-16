<?php
use yii\helpers\Url;

$action = $this->context->action->id;
$module = $this->context->module->id;

$backTo = null;
$indexUrl = Url::to(['/admin/'.$module]);
$noanswerUrl = Url::to(['/admin/'.$module.'/a/noanswer']);

if($action === 'view')
{
    $returnUrl = $this->context->getReturnUrl(['/admin/'.$module]);

    if(strpos($returnUrl, 'noanswer') !== false){
        $backTo = 'noanswer';
        $noanswerUrl = $returnUrl;
    } else {
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
            <?= Yii::t('easyii', 'All') ?>
        </a>
    </li>
    <li <?= ($action === 'noanswer') ? 'class="active"' : '' ?>>
        <a href="<?= $noanswerUrl ?>">
            <?php if($backTo === 'noanswer') : ?>
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?php endif; ?>
            <?= Yii::t('easyii/guestbook', 'No answer') ?>
            <?php if($this->context->noAnswer > 0) : ?>
                <span class="badge"><?= $this->context->noAnswer ?></span>
            <?php endif; ?>
        </a>
    </li>
    <li class="pull-right">
        <?php if($action === 'view') : ?>
            <a href="<?= Url::to(['/admin/'.$module.'/a/setnew', 'id' => Yii::$app->request->get('id')]) ?>" class="text-warning"><span class="glyphicon glyphicon-eye-close"></span> <?= Yii::t('easyii/guestbook', 'Mark as new') ?></a>
        <?php else : ?>
            <a href="<?= Url::to(['/admin/'.$module.'/a/viewall']) ?>" class="text-warning"><span class="glyphicon glyphicon-eye-open"></span> <?= Yii::t('easyii/guestbook', 'Mark all as viewed') ?></a>
        <?php endif; ?>
    </li>
</ul>
<br/>
