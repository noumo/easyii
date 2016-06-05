<?php
/**
 * @var \yii\web\View                                                   $this
 * @var                                                                 $content
 * @var \yii\easyii\modules\content\modules\contentElements\models\BaseElement $element
 */

?>
<?php $this->beginPage() ?>

<li id="element_<?= $element->primaryKey ?>"
	class="sortable-item"
	data-name="<?= $element->type ?>"
	data-element-id="<?= $element->primaryKey ?>"
	data-element-type="<?= $element->type ?>"
	data-element-scenario="<?= $element->scenario ?>">

	<?php $this->beginBody() ?>

	<?php require '_header.php' ?>

	<?= \yii\helpers\Html::tag('div',
		$content,
		[
			'id' => "element_content_{$element->primaryKey}",
			'class' => $element->collapsible ? 'collapse' : '',
		]) ?>

	<?php $this->endBody() ?>

</li>

<?php $this->endPage(true) ?>
