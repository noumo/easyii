<?php
/**
 * @var \yii\web\View                                                            $this
 * @var \yii\easyii\modules\content\modules\contentElements\elements\others\module\models\Element $element
 * @var $modules
 */

use yii\helpers\Html;
use \yii\easyii\modules\content\modules\contentElements\elements\others\module\models\Element;

?>

<?= Html::activeHiddenInput($element, 'type'); ?>

<div class="row">
	<div class="col-lg-5">
		<?= Html::activeLabel($element, 'module', ['class' => 'form-label']); ?>
		<?= Html::activeDropDownList($element, 'module', $modules, ['class' => 'form-control dynamic-module']); ?>
	</div>

	<div class="col-lg-5">
		<?= Html::activeLabel($element, 'function', ['class' => 'form-label']); ?>
		<?= \yii\jui\AutoComplete::widget([
			'model' => $model,
			'attribute' => 'country',
			'clientOptions' => [
				'source' => ['USA', 'RUS'],
			],
		]); ?>
		<?= Html::activeTextInput($element, 'function', ['class' => 'form-control dynamic-function']); ?>
	</div>

	<div class="col-lg-5">
		<?= Html::activeLabel($element, 'widgetClass', ['class' => 'form-label']); ?>
		<?= Html::activeDropDownList($element, 'widgetClass', Element::$widgets, ['class' => 'form-control dynamic-widgetClass']); ?>
	</div>
</div>