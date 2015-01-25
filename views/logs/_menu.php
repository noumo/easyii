<?php
$action = $this->context->action->id;
?>
<ul class="nav nav-pills">
    <li <?= ($action==='index') ? 'class="active"' : '' ?>><a href="/admin/logs"><?= Yii::t('easyii', 'Sign in') ?></a></li>
</ul>
<br/>
