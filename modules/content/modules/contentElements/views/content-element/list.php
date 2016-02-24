<?php
/**
 * @var array $groupItems
 */

use \yii\helpers\Html;

?>
<?php foreach ($groupItems as $group => $items) : ?>

	<h1><?= \yii\helpers\Inflector::titleize($group) ?></h1>
	<?= Html::ul($items, [
		'encode' => false,
		'class' => 'list-inline',
		'item' => function ($item, $type) use ($group) {
			$icon = '<i class="glyphicon glyphicon-plus font-12"></i> ';
			$text = $icon . Yii::t('easyii/content', $item['title']);
			return Html::button($text, ['class' => 'btn btn-default', 'data-content-element' => $item['id']]);
		}
	]);?>

<?php endforeach; ?>