<?php
/**
 * @var \yii\web\View                                                   $this
 * @var                                                                 $content
 * @var array                                                           $config
 * @var \yii\easyii\modules\content\modules\contentElements\models\BaseElement $element
 */
use yii\helpers\Html;

?>
<div class="content-element-header">
	<div class="btn-group">
		<?= Html::tag('h4', $config['title']) ?>

		<?= Html::errorSummary($element, ['class' => 'alert alert-danger']) ?>
	</div>

	<?php if (!$element->readOnly): ?>
		<div class="btn-group btn-group-sm pull-right" role="group">
			<a href="#" class="btn btn-default move-up" title="<?= Yii::t('easyii', 'Move up') ?>"><span class="glyphicon glyphicon-arrow-up"></span></a>
			<a href="#" class="btn btn-default move-down" title="<?= Yii::t('easyii', 'Move down') ?>"><span class="glyphicon glyphicon-arrow-down"></span></a>
			<a href="#" class="btn btn-default color-red js-remove confirm-delete" data-reload="1" title="<?= Yii::t('easyii', 'Delete item') ?>">
				<span class="glyphicon glyphicon-remove"></span>
			</a>
		</div>
	<?php endif ?>

	<?php require '_options.php' ?>

	<?= Html::activeHiddenInput($element, 'element_id'); ?>
	<?= Html::activeHiddenInput($element, 'parent_element_id'); ?>
	<?= Html::activeHiddenInput($element, 'type'); ?>
	<?= Html::activeHiddenInput($element, 'scenario'); ?>
</div>
