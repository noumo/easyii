<?php
/**
 * @var \yii\web\View                                                                   $this
 * @var string $widgetId
 * @var \yii\easyii\modules\content\modules\contentElements\elements\standard\container\models\Element $element
 */

use yii\bootstrap\Modal;
use yii\helpers\Url;
use \yii\easyii\modules\content\modules\contentElements\elements\standard\container\ContainerAsset;

ContainerAsset::register($this);
?>

<div id="<?= $widgetId ?>">
	<h3>
		<?= Yii::t('easyii/content', 'Content elements') ?>

		<?php Modal::begin([
			'id' => 'elementModal',
			'header' => Yii::t('easyii/content', 'Select element type'),
			'toggleButton' => [
				'label' => '<i class="glyphicon glyphicon-plus font-12"></i> ' . Yii::t('easyii/content', 'Add element'),
				'class' => 'btn btn-default',
				'id' => 'addElement',
				'data-target' => "#$widgetId #elementModal"
			],
			'options' => [
				'data-modal-source' => Url::to(['/admin/content/contentElements/content-element/list']),
			],
		]); ?>

		<div class="content" id="mainContent">
			<?= $content ?>
		</div>

		<?php Modal::end(); ?>
	</h3>

	<table class="table table-hover elementListView">
		<thead>
		<th></th>
		<th width="120"></th>
		</thead>
		<tbody>
		<?php foreach ($element->elements as $subElement) : ?>
			<?= $subElement->render($this) ?>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>