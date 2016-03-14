<?php
/**
 * @var \yii\web\View                                                                   $this
 * @var \yii\easyii\modules\content\modules\contentElements\elements\standard\image\models\Element $element
 */

use yii\helpers\Html;
$module = Yii::$app->controller->module->id;
$settings = Yii::$app->controller->module->settings;
?>

<div class="form-inline">
	<div class="form-group">
		<?= Html::activeLabel($element, 'source', ['class' => 'form-label']); ?>
		<?php if ($settings['itemThumb']) : ?>
			<?php if ($element->source) : ?>
				<img src="<?= \yii\easyii\helpers\Image::thumb($element->source, 240) ?>">
				<a href="<?= \yii\helpers\Url::to(['/admin/' . $module . '/content-element/run', 'id' => $element->primaryKey, 'action' => 'clear-image']) ?>"
				   class="text-danger confirm-delete"
				   title="<?= Yii::t('easyii', 'Clear image') ?>"><?= Yii::t('easyii', 'Clear image') ?></a>
			<?php endif; ?>
		<?php endif; ?>

		<?= Html::activeFileInput($element, 'source', ['class' => 'form-control-static']); ?>
		<?= Html::activeHiddenInput($element, 'source'); ?>
	</div>
</div>

<?= Html::activeLabel($element, 'altText', ['class' => 'form-label']); ?>
<?= Html::activeTextInput($element, 'altText', ['class' => 'form-control']); ?>
<?= Html::activeLabel($element, 'title', ['class' => 'form-label']); ?>
<?= Html::activeTextInput($element, 'title', ['class' => 'form-control']); ?>
