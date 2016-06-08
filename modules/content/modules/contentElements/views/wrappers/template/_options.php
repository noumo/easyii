<?php
/**
 * @var \yii\web\View                                                   $this
 * @var                                                                 $content
 * @var array                                                           $config
 * @var \yii\easyii\modules\content\modules\contentElements\models\BaseElement $element
 */
use yii\helpers\Html;
use \yii\easyii\modules\content\modules\contentElements\models\BaseElement;

$inputId = Html::getInputId($element, 'options');
$inputName = Html::getInputName($element, 'options');

?>
<?= Html::a(Yii::t('easyii/content', 'Show options'), "#$inputId", ['data-toggle' => "collapse"]); ?>

<?= Html::beginTag('div', ['id' => $inputId, 'class' => 'panel-collapse collapse']) ?>

	<div class="panel-body">
		<?php foreach ($element->options as $key => $option) : ?>
			<?= Html::activeLabel($option, 'type') ?>
			<?= Html::activeTextInput($option, 'value', ['name' => $inputName . "[$key][value]"]) ?>
			<?= Html::activeHiddenInput($option, 'option_id', ['name' => $inputName . "[$key][option_id]"]) ?>
			<?= Html::activeHiddenInput($option, 'scenario', ['name' => $inputName . "[$key][scenario]"]) ?>
		<?php endforeach; ?>

		<?= Html::activeLabel($option, 'wrapper') ?>
		<?= Html::activeDropDownList($element, 'wrapper', BaseElement::wrappers(), ['prompt' => Yii::t('easyii/content', 'Default')]); ?>
	</div>


<?= Html::endTag('div'); ?>