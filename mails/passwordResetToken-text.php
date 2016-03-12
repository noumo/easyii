<?php
/* @var $this yii\web\View */
/* @var $resetPassword \yii\easyii\models\ResetPassword */
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['admin/sign/reset-password', 'token' => $resetPassword->token]);
?>
	Hello <?= $resetPassword->email ?>,

	Follow the link below to reset your password:

<?= $resetLink ?>