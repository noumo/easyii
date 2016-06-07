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

		<?= Html::activeLabel($element, 'format', ['class' => 'form-label']); ?>
		<?= Html::activeDropDownList($element, 'format', Element::$formats, ['class' => 'form-control dynamic-widgetClass']); ?>
	</div>

	<div class="col-lg-5">
		<?= Html::activeLabel($element, 'function', ['class' => 'form-label']); ?>
		<?= \yii\jui\AutoComplete::widget([
			'model' => $element,
			'attribute' => 'function',
			'clientOptions' => [
				'source' => \yii\helpers\Url::to(['content-element/run', 'id' => $element->primaryKey, 'action' => 'module-functions']),
				'select' => new \yii\web\JsExpression('
					function(event, ui) {
						$(this).siblings("input:hidden").val(ui.item.value);
						event.preventDefault();
						$(this).val(ui.item.label);
					}'),
				'search' => new \yii\web\JsExpression('function( event, ui ) {
					var source = $(this).autocomplete("option", "source");
					source += "&module=" + $(".dynamic-module").val();

					$(this).autocomplete("option", "source", source)
				}'),
			],

			'options' => [
				'class' => 'form-control header-content'
			]
		]); ?>

		<?= Html::activeLabel($element, 'parameters', ['class' => 'form-label']); ?>
		<?= Html::activeTextInput($element, 'parameters', ['class' => 'form-control dynamic-parameters']); ?>
	</div>
</div>