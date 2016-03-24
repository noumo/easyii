<?php
/**
 * @var \yii\web\View                                                   $this
 * @var                                                                 $content
 * @var \yii\easyii\modules\content\modules\contentElements\models\BaseElement $element
 */
use yii\helpers\Html;

?>
<?php $this->beginPage() ?>

<li id="element_<?= $element->primaryKey ?>"
	data-name="<?= $element->type ?>"
	data-element-id="<?= $element->primaryKey ?>"
	data-element-type="<?= $element->type ?>"
	data-element-scenario="<?= $element->scenario ?>">

	<?php $this->beginBody() ?>

	<?php require '_header.php' ?>

	<?= $content ?>

	<?php $this->endBody() ?>

</li>

<?php $this->endPage(true) ?>
