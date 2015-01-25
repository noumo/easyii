<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\BootstrapPluginAsset;

use yii\easyii\assets\EmptyAsset;

BootstrapPluginAsset::register($this);
$asset = EmptyAsset::register($this);
$this->registerJs('$("input").tooltip({ placement: "right", trigger: "focus" })');

$this->title = Yii::t('easyii/install', 'Installation');
?>
<div class="container">
    <div id="wrapper" class="col-md-6 col-md-offset-3 vertical-align-parent">
        <div class="vertical-align-child">
            <div class="panel">
                <div class="panel-heading text-center">
                    <?= Yii::t('easyii/install', 'Installation') ?>
                </div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(); ?>
                    <?= $form->field($model, 'root_password', ['inputOptions' => ['title' => Yii::t('easyii/install','Password to login as root')]]) ?>
                    <?= $form->field($model, 'admin_email', ['inputOptions' => ['title' => Yii::t('easyii/install','Used as "ReplyTo" in mail messages')]]) ?>
                    <?= $form->field($model, 'robot_email', ['inputOptions' => ['title' => Yii::t('easyii/install','Used as "From" in mail messages')]]) ?>
                    <?= $form->field($model, 'recaptcha_key', ['inputOptions' => ['title' => Yii::t('easyii/install','Required for using captcha in forms (guestbook, feedback)')]]) ?>
                    <?= $form->field($model, 'recaptcha_secret') ?>
                    <p class="recaptcha-tip"><?= Yii::t('easyii/install', 'You easily can get keys on') ?> <a href="https://www.google.com/recaptcha/intro/index.html" target="_blank"><?= Yii::t('easyii/install', 'ReCaptcha website') ?></a></p>
                    <?= Html::submitButton(Yii::t('easyii/install', 'Install'), ['class' => 'btn btn-lg btn-primary btn-block']) ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            <div class="text-center">
                <a class="logo" href="http://easyiicms.com" target="_blank" title="EasyiiCMS homepage">
                    <img src="<?= $asset->baseUrl ?>/img/logo_20.png">EasyiiCMS
                </a>
            </div>
        </div>
    </div>
</div>
