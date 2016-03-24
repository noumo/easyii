<?php
/**
 * @var \yii\web\View                                                                      $this
 * @var \yii\easyii\modules\content\modules\contentElements\elements\standard\heading\models\Element $element
 */

use yii\helpers\Html;
?>

<div class="row form-group">
	<div class="col-md-2">
		<?= Html::activeLabel($element, 'number', ['class' => 'form-label']); ?>
		<?= Html::activeDropDownList($element, 'number', $element::getHeadings(), ['class' => 'form-control header-number']); ?>
	</div>

	<div class="col-md-10">
		<?= Html::activeLabel($element, 'content', ['class' => 'form-label']); ?>
		<?= Html::activeTextInput($element, 'content', ['class' => 'form-control header-content']); ?>
	</div>
</div>
