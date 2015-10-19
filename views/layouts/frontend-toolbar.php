<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\easyii\assets\FrontendAsset;
use yii\easyii\models\Setting;

$asset = FrontendAsset::register($this);
$position = Setting::get('toolbar_position') === 'bottom' ? 'bottom' : 'top';
$this->registerCss('body {padding-'.$position.': 50px;}');
?>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
<nav id="easyii-navbar">
    <div class="easyii-container">
        <a href="<?= Url::to(['/admin']) ?>" class="pull-left"><span class="glyphicon glyphicon-arrow-left"></span> <?= Yii::t('easyii', 'Control Panel') ?></a>
        <div class="live-edit-label pull-left">
            <i class="glyphicon glyphicon-pencil"></i>
            <?= Yii::t('easyii', 'Live edit') ?>
        </div>
        <div class="live-edit-checkbox pull-left">
            <?= Html::checkbox('', LIVE_EDIT, ['data-link' => Url::to(['/admin/system/live-edit'])]) ?>
        </div>
        <a href="<?= Url::to(['/admin/sign/out']) ?>" class="pull-right"><span class="glyphicon glyphicon-log-out"></span> <?= Yii::t('easyii', 'Logout') ?></a></li>
    </div>
</nav>