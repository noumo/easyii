<?php
/**
 * @var \yii\web\View                                                                   $this
 * @var string $widgetId
 * @var \yii\easyii\modules\content\modules\contentElements\elements\standard\container\models\Element $element
 */

use yii\bootstrap\Modal;
use yii\helpers\Url;
use \yii\easyii\modules\content\modules\contentElements\elements\standard\container\ContainerAsset;
use \yii\easyii\modules\content\modules\contentElements\BaseElement;

ContainerAsset::register($this);
?>

<div id="<?= $widgetId ?>">
	<h3>
		<?= Yii::t('easyii/content', 'Content elements') ?>

		<?php # \yii\helpers\Html::button('<i class="glyphicon glyphicon-plus font-12"></i> Add element', ['id' => $widgetId . '-addElement', 'class' => 'btn btn-default']); ?>

		<?php Modal::begin([
			'id' => "$widgetId-elementModal",
			'header' => Yii::t('easyii/content', 'Select element type'),
			'toggleButton' => [
				'label' => '<i class="glyphicon glyphicon-plus font-12"></i> ' . Yii::t('easyii/content', 'Add element'),
				'class' => 'btn btn-default',
				'id' => "$widgetId-addElement",
			],
			'options' => [
				'data-parent-id' => $element->element_id,
				'data-list-source' => Url::to(['/admin/content/contentElements/content-element/list']),
				'data-template-source' => Url::to(['/admin/content/contentElements/content-element/template']),
			],
		]); ?>

		<div class="content" id="mainContent">
			<?= $content ?>
		</div>

		<?php Modal::end(); ?>
	</h3>

	<?= \yii\easyii\modules\content\modules\contentElements\widgets\EditableList::widget([
		'items' => $element->elements,
		'render' => function (BaseElement $item, $index) {
			return $item->render($this);
		},
		'addButton' => "$widgetId-addElement",
		'modalSelector' => "$widgetId-elementModal",
	]) ?>
</div>
