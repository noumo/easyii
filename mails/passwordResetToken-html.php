<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $resetPassword \yii\easyii\models\ResetPassword */
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['admin/sign/reset-password', 'token' => $resetPassword->token]);
?>
<div class="password-reset">
	<p>Hello <?= Html::encode($resetPassword->email) ?>,</p>

	<p>Follow the link below to reset your password:</p>

	<p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>