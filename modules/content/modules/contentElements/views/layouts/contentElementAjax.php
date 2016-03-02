<?php
/**
 * @var \yii\web\View                                                   $this
 * @var                                                                 $content
 * @var \yii\easyii\modules\content\modules\contentElements\BaseElement $element
 */
use yii\helpers\Html;

?>
<?php $this->beginPage() ?>

<div data-element-id="<?= $element->primaryKey ?>"
	 data-element-type="<?= $element->type ?>"
	 data-element-scenario="<?= $element->scenario ?>">

	<?= Html::tag('h4', $config['title']) ?>

	<?= Html::errorSummary($element, ['class' => 'alert alert-danger']) ?>

	<?= Html::activeHiddenInput($element, 'element_id'); ?>
	<?= Html::activeHiddenInput($element, 'parent_element_id'); ?>
	<?= Html::activeHiddenInput($element, 'type'); ?>
	<?= Html::activeHiddenInput($element, 'scenario'); ?>

	<?= $content ?>

	<?php $this->endBody() ?>

</div>

<?php $this->endPage(true) ?>
