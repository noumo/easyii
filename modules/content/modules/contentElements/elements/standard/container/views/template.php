<?php
/**
 * @var \yii\web\View                                                                   $this
 * @var string $widgetId
 * @var \yii\easyii\modules\content\modules\contentElements\elements\standard\container\models\Element $element
 */

use yii\bootstrap\Modal;
use yii\helpers\Url;
use \yii\easyii\modules\content\modules\contentElements\BaseElement;

?>

<div id="<?= $widgetId ?>">
	<?php Modal::begin([
		'id' => "$widgetId-elementModal",
		'header' => Yii::t('easyii/content', 'Select element type'),
		'toggleButton' => [
			'label' => '<i class="glyphicon glyphicon-plus font-12"></i> ' . Yii::t('easyii/content', 'Add content element'),
			'class' => 'btn btn-default',
			'id' => "$widgetId-addElement",
		],
		'options' => [
			'data-parent-id' => $element->element_id,
			'data-list-source' => Url::to(['/admin/content/contentElements/content-element/list']),
		],
	]); ?>

	<div class="content" id="mainContent">
		<?= $content ?>
	</div>

	<?php Modal::end(); ?>
</div>

<?= \yii\easyii\modules\content\modules\contentElements\widgets\EditableList::widget([
	'items' => $element->elements,
	'render' => function (BaseElement $item, $index) {
		return $item->render($this);
	},
	'templateUrl' => Url::to(['/admin/content/contentElements/content-element/template']),
	'deleteUrl' => Url::to(['/admin/content/contentElements/content-element/delete']),
	'addButton' => "$widgetId-addElement",
	'modalSelector' => "$widgetId-elementModal",
	'rootId' => (is_null($element->parent_element_id) ? $element->element_id : $element->parent_element_id),
]) ?>
