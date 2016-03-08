<?php
/**
 * @var \yii\web\View                                                   $this
 * @var                                                                 $content
 * @var \yii\easyii\modules\content\modules\contentElements\BaseElement $element
 */
use yii\helpers\Html;

?>
<?php $this->beginPage() ?>

<li id="element_<?= $element->primaryKey ?>"
	data-element-id="<?= $element->primaryKey ?>"
	data-element-type="<?= $element->type ?>"
	data-element-scenario="<?= $element->scenario ?>">

	<div class="">
		<div class="btn-group">
			<?= Html::tag('h4', $config['title']) ?>

			<?= Html::errorSummary($element, ['class' => 'alert alert-danger']) ?>
		</div>

		<div class="btn-group btn-group-sm pull-right" role="group">
			<a href="#" class="btn btn-default move-up" title="<?= Yii::t('easyii', 'Move up') ?>"><span class="glyphicon glyphicon-arrow-up"></span></a>
			<a href="#" class="btn btn-default move-down" title="<?= Yii::t('easyii', 'Move down') ?>"><span class="glyphicon glyphicon-arrow-down"></span></a>
			<a href="#" class="btn btn-default color-red js-remove" title="<?= Yii::t('easyii', 'Delete item') ?>"><span class="glyphicon glyphicon-remove"></span></a>
		</div>

		<?= Html::activeHiddenInput($element, 'element_id'); ?>
		<?= Html::activeHiddenInput($element, 'parent_element_id'); ?>
		<?= Html::activeHiddenInput($element, 'type'); ?>
		<?= Html::activeHiddenInput($element, 'scenario'); ?>
	</div>

	<?= $content ?>

	<?php $this->endBody() ?>

</li>

<?php $this->endPage(true) ?>
