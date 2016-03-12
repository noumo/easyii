<?php
/**
 * @var \yii\easyii\models\ResetPassword $model
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$asset = \yii\easyii\assets\EmptyAsset::register($this);
$this->title = Yii::t('easyii', 'Reset password');
?>
<div class="container">
	<div id="wrapper" class="col-md-4 col-md-offset-4 vertical-align-parent">
		<div class="vertical-align-child">
			<div class="panel">
				<div class="panel-heading text-center">
					<?= Yii::t('easyii', 'Please choose your new password') ?>
				</div>
				<div class="panel-body">
					<?php $form = ActiveForm::begin([
						'id' => 'reset-password-form',
						'fieldConfig' => [
							'template' => "{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}"
						]
					])
					?>
					<?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>

					<?=Html::submitButton(Yii::t('easyii', 'Reset'), ['class'=>'btn btn-lg btn-primary btn-block']) ?>
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
