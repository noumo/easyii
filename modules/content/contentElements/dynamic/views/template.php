<?php
/**
 * @var \yii\web\View                                                             $this
 * @var \yii\easyii\modules\content\contentElements\dynamic\models\DynamicElement $element
 */

use yii\helpers\Html;

?>

<?= Html::activeHiddenInput($element, 'type'); ?>

<div class="row">
	<div class="col-lg-5">
		<?= Html::activeLabel($element, 'module', ['class' => 'form-label']); ?>
		<?= Html::activeTextInput($element, 'module', ['class' => 'form-control dynamic-module']); ?>
	</div>

	<div class="col-lg-5">
		<?= Html::activeLabel($element, 'function', ['class' => 'form-label']); ?>
		<?= Html::activeTextInput($element, 'function', ['class' => 'form-control dynamic-function']); ?>
	</div>

	<div class="col-lg-5">
		<?= Html::activeLabel($element, 'widget', ['class' => 'form-label']); ?>
		<?= Html::activeDropDownList($element, 'widget', $element::WIDGETS, ['class' => 'form-control dynamic-widget']); ?>
	</div>
</div>