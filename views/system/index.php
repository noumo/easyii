<?php
use yii\easyii\models\Setting;
use yii\helpers\Url;

$this->title = Yii::t('easyii', 'System');
?>
<?= $this->render('_menu') ?>

<h4><?= Yii::t('easyii', 'Current version') ?>: <b><?= Setting::get('easyii_version') ?></b>
    <?php if(\yii\easyii\AdminModule::VERSION > floatval(Setting::get('easyii_version'))) : ?>
        <a href="<?= Url::to(['/admin/system/update']) ?>" class="btn btn-success"><?= Yii::t('easyii', 'Update') ?></a>
    <?php endif; ?>
</h4>

<br>

<p>
    <a href="<?= Url::to(['/admin/system/flush-cache']) ?>" class="btn btn-default"><i class="glyphicon glyphicon-flash"></i> <?= Yii::t('easyii', 'Flush cache') ?></a>
</p>

<br>

<p>
    <a href="<?= Url::to(['/admin/system/clear-assets']) ?>" class="btn btn-default"><i class="glyphicon glyphicon-trash"></i> <?= Yii::t('easyii', 'Clear assets') ?></a>
</p>