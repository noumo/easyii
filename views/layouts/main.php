<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\easyii\assets\AdminAsset;
use yii\easyii\components\Module as EasyiiModule;
use \yii\easyii\controllers\HelpController;

$asset = AdminAsset::register($this);
$moduleName = $this->context->module->id;
?>
<?php $this->beginPage() ?>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Yii::t('easyii', 'Control Panel') ?> - <?= Html::encode($this->title) ?></title>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link rel="shortcut icon" href="<?= $asset->baseUrl ?>/favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?= $asset->baseUrl ?>/favicon.ico" type="image/x-icon">
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div id="admin-body">
    <div class="container">
        <div class="wrapper">
            <div class="header">
                <a href="<?= Url::to(['/admin']) ?>" class="logo">
                    <img src="<?= $asset->baseUrl ?>/img/logo_20.png">EasyiiCMS
                </a>
                <div class="nav">
                    <a href="<?= Url::to(['/']) ?>" class="pull-left"><i class="glyphicon glyphicon-home"></i> <?= Yii::t('easyii', 'Open site') ?></a>
                    <a href="<?= Url::to(['/admin/sign/out']) ?>" class="pull-right"><i class="glyphicon glyphicon-log-out"></i> <?= Yii::t('easyii', 'Logout') ?></a>
                </div>
            </div>
            <div class="main">
                <div class="box sidebar">
                    <?php foreach(Yii::$app->getModule('admin')->activeModules as $module) : ?>
                        <a href="<?= Url::to(["/admin/$module->name"]) ?>" class="menu-item <?= ($moduleName == $module->name ? 'active' : '') ?>">
                            <?php if($module->icon != '') : ?>
                                <i class="glyphicon glyphicon-<?= $module->icon ?>"></i>
                            <?php endif; ?>
                            <?= $module->title ?>
                            <?php if($module->notice > 0) : ?>
                                <span class="badge"><?= $module->notice ?></span>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                    <a href="<?= Url::to(['/admin/settings']) ?>" class="menu-item <?= ($moduleName == 'admin' && $this->context->id == 'settings') ? 'active' :'' ?>">
                        <i class="glyphicon glyphicon-cog"></i>
                        <?= Yii::t('easyii', 'Settings') ?>
                    </a>
                    <?php if(IS_ROOT) : ?>
                        <a href="<?= Url::to(['/admin/modules']) ?>" class="menu-item <?= ($moduleName == 'admin' && $this->context->id == 'modules') ? 'active' :'' ?>">
                            <i class="glyphicon glyphicon-folder-close"></i>
                            <?= Yii::t('easyii', 'Modules') ?>
                        </a>
                        <a href="<?= Url::to(['/admin/admins']) ?>" class="menu-item <?= ($moduleName == 'admin' && $this->context->id == 'admins') ? 'active' :'' ?>">
                            <i class="glyphicon glyphicon-user"></i>
                            <?= Yii::t('easyii', 'Admins') ?>
                        </a>
                        <a href="<?= Url::to(['/admin/system']) ?>" class="menu-item <?= ($moduleName == 'admin' && $this->context->id == 'system') ? 'active' :'' ?>">
                            <i class="glyphicon glyphicon-hdd"></i>
                            <?= Yii::t('easyii', 'System') ?>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="box content">
                    <?php if (!Yii::$app->controller instanceof HelpController) : ?>
                        <div class="page-title">
                            <?= $this->title ?>
                            <?php if ($this->context->module->showHelp) : ?>
                                <?= Html::a('<i class="glyphicon glyphicon-question-sign"></i>',
                                    $this->context->module->id . '/help',
                                    ['class' => 'pull-right', 'title' => Yii::t('easyii', 'Show help')]) ?>
                            <?php endif ?>
                        </div>
                    <?php endif ?>
                    <div class="container-fluid">
                        <?php foreach(Yii::$app->session->getAllFlashes() as $key => $message) : ?>
                            <?php if (is_array($message)) : ?>
                                <?php Html::ul($message, ['item' => function ($message, $key) {
                                    return Html::tag('div', $message, ['class' => "alert alert-$key"]);
                                }])?>
                            <?php else: ?>
                                <div class="alert alert-<?= $key ?>"><?= $message ?></div>
                            <?php endif ?>
                        <?php endforeach; ?>
                        <?= $content ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
