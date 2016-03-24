<?php
use yii\helpers\Url;

$action = $this->context->action->id;
$categoryName = (new ReflectionClass($this->context->categoryClass))->getShortName();
?>
<ul class="nav nav-pills">
    <li <?= ($action === 'index') ? 'class="active"' : '' ?>>
        <a href="<?= $this->context->getReturnUrl(['/admin/'.$this->context->module->id.'/']) ?>">
            <?php if($action != 'index') : ?>
                <i class="glyphicon glyphicon-chevron-left font-12"></i>
            <?php endif; ?>
            <?= Yii::t('easyii', $categoryName) ?>
        </a>
    </li>
    <li <?= ($action === 'create') ? 'class="active"' : '' ?>><a href="<?= Url::to(['create']) ?>"><?= Yii::t('easyii', 'Create {categoryName}', ['categoryName' => $categoryName]) ?></a></li>
</ul>
<br/>