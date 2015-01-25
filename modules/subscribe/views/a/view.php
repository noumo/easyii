<?php
$this->title = Yii::t('easyii/subscribe', 'View subscribe history');
$this->registerCss('.subscribe-view dt{margin-bottom: 10px;}');
?>
<?= $this->render('_menu') ?>

<dl class="dl-horizontal subscribe-view">
    <dt><?= Yii::t('easyii/subscribe', 'Subject') ?></dt>
    <dd><?= $model->subject ?></dd>

    <dt><?= Yii::t('easyii', 'Date') ?></dt>
    <dd><?= Yii::$app->formatter->asDatetime($model->time, 'medium') ?></dd>

    <dt><?= Yii::t('easyii/subscribe', 'Sent') ?></dt>
    <dd><?= $model->sent ?></dd>

    <dt><?= Yii::t('easyii/subscribe', 'Body') ?></dt>
    <dd></dd>
</dl>
<?= $model->body ?>