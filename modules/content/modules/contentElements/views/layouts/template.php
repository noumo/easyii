<?php
/**
 * @var \yii\web\View                                                   $this
 * @var                                                                 $content
 * @var array                                                           $config
 * @var \yii\easyii\modules\content\modules\contentElements\models\BaseElement $element
 */

?>

<li id="element_<?= $element->primaryKey ?>"
	class="sortable-item"
	data-name="<?= $element->type ?>"
	data-element-id="<?= $element->primaryKey ?>"
	data-element-type="<?= $element->type ?>"
	data-element-scenario="<?= $element->scenario ?>">

	<?php require '_header.php' ?>

	<?= $content ?>
</li>


