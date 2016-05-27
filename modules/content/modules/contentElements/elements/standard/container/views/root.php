<?php
/**
 * @var \yii\web\View                                                                   $this
 * @var string $widgetId
 * @var \yii\easyii\modules\content\modules\contentElements\elements\standard\container\models\Element $element
 */

use yii\bootstrap\Modal;
use yii\helpers\Url;
use \yii\easyii\modules\content\modules\contentElements\models\BaseElement;

?>

<?= \yii\easyii\modules\content\modules\contentElements\widgets\EditableList::widget([
	'items' => [$element],
	'render' => function (BaseElement $item, $index) {
		return $item->render($this);
	},
	'templateUrl' => Url::to(['/admin/content/contentElements/content-element/template']),
	'deleteUrl' => Url::to(['/admin/content/contentElements/content-element/delete']),
	'addButton' => "$widgetId-addElement",
	'modalSelector' => "$widgetId-elementModal",
	'rootId' => (is_null($element->parent_element_id) ? $element->element_id : $element->parent_element_id),
	'htmlOptions' => [
		'class' => 'root'
	]
]) ?>
