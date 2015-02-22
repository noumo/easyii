<?php
use yii\easyii\models\Setting;

$this->title = Yii::t('easyii', 'System');
?>

<h4><?= Yii::t('easyii', 'Current version') ?>: <b><?= Setting::get('easyii_version') ?></b>
    <?php if(\yii\easyii\AdminModule::VERSION > floatval(Setting::get('easyii_version'))) : ?>
        <a href="/admin/system/update" class="btn btn-success"><?= Yii::t('easyii', 'Update') ?></a>
    <?php endif; ?>
</h4>

<br>

<p>
    <a href="/admin/system/flush-cache" class="btn btn-default"><i class="glyphicon glyphicon-flash"></i> <?= Yii::t('easyii', 'Flush cache') ?></a>
</p>

<br>

<p>
    <a href="/admin/system/clear-assets" class="btn btn-default"><i class="glyphicon glyphicon-trash"></i> <?= Yii::t('easyii', 'Clear assets') ?></a>
</p>