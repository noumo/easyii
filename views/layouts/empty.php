<?php
use yii\helpers\Html;

$asset = \yii\easyii\assets\EmptyAsset::register($this);
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
<div class="container">
    <?= $content ?>
</div>
<?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>