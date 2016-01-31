<?php
/**
 * @var \yii\web\View $this
 * @var \yii\easyii\modules\content\models\Item $model
 */

use yii\bootstrap\Modal;
use yii\easyii\modules\content\assets\ElementsAsset;
use yii\helpers\Url;

ElementsAsset::register($this);

$options = [
	'templateUrl' => Url::to(['/admin/'.$this->context->module->id.'/content-element/template']),
	'showModalSelector' => '#addElement',
	'modalSelector' => '#elementModal'
];
$options = \yii\helpers\Json::encode($options);
$this->registerJs("$('.elementListView').elementListView($options);", \yii\web\View::POS_END);

?>

<h3>
	<?= Yii::t('easyii/content', 'Content elements') ?>

	<?php Modal::begin([
		'id' => 'elementModal',
		'header' =>  Yii::t('easyii/content', 'Select element type'),
		'toggleButton' => [
			'label' => '<i class="glyphicon glyphicon-plus font-12"></i> ' . Yii::t('easyii/content', 'Add element'),
			'class' => 'btn btn-default',
			'id' => 'addElement',
		],
		'options' => [
			'data-modal-source' => Url::to(['/admin/'.$this->context->module->id.'/content-element/list']),
		]
	]); ?>

	<div class="content" id="mainContent">
		<?= $content ?>
	</div>

	<?php Modal::end(); ?>
</h3>

<table id="elementListView" class="table table-hover elementListView">
    <thead>
        <th></th>
        <th width="120"></th>
    </thead>
    <tbody>
    <?php foreach($model->elements as $element) : ?>
	    <?= $element->render($this) ?>
    <?php endforeach; ?>
    </tbody>
</table>