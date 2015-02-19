<?php
use yii\easyii\models\Setting;

$this->title = Yii::t('easyii', 'System');
?>

<h4>Current verison: <b><?= Setting::get('easyii_version') ?></b>
    <?php if(\yii\easyii\AdminModule::VERSION > floatval(Setting::get('easyii_version'))) : ?>
        <a href="/admin/system/update" class="btn btn-success">Update</a>
    <?php endif; ?>
</h4>

<br>

<p>
    <a href="/admin/system/flush-cache" class="btn btn-default"><i class="glyphicon glyphicon-flash"></i> <?= strtoupper(Yii::t('easyii', 'Flush cache')) ?></a> <span class="smooth">Flushes all system cache (table schemas, settings, etc.)</span>
</p>

<br>

<p>
    <a href="/admin/system/clear-assets" class="btn btn-default"><i class="glyphicon glyphicon-trash"></i> <?= strtoupper(Yii::t('easyii', 'Clear assets')) ?></a> <span class="smooth">Deletes all assets files.</span>
</p>