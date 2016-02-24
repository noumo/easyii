<?php
/**
 * @var \yii\web\View                                                                   $this
 * @var \yii\easyii\modules\content\modules\contentElements\elements\standard\container\models\Element $element
 */
use \yii\easyii\modules\content\modules\contentElements\ContentElementModule;
?>

<?php foreach ($element->elements as $subElement) : ?>
	<?= ContentElementModule::createWidget($subElement)->run(); ?>
<?php endforeach; ?>