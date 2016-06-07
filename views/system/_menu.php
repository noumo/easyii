<?php
use yii\helpers\Url;

$action = $this->context->action->id;
?>
<ul class="nav nav-pills">
    <li <?= ($action === 'index') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/system']) ?>"><?= Yii::t('easyii', 'Tools') ?></a></li>
    <li <?= ($action === 'logs') ? 'class="active"' : '' ?>><a href="<?= Url::to(['/admin/system/logs']) ?>"><?= Yii::t('easyii', 'Logs') ?></a></li>
</ul>
<br/>