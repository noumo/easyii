<?php
/**
 * @var \yii\web\View $this
 * @var \yii\easyii\modules\content\models\Item $model
 */
use yii\easyii\modules\content\assets\ElementsAsset;
use yii\helpers\Html;

ElementsAsset::register($this);

$options = [
	'templateUrl' => \yii\helpers\Url::to(['/admin/'.$this->context->module->id.'/content-element/template'])
];
$options = \yii\helpers\Json::encode($options);
$this->registerJs("
$('.elementListView').elementListView($options);
", \yii\web\View::POS_END);

?>

<?= Html::button('<i class="glyphicon glyphicon-plus font-12"></i> '.Yii::t('easyii/content', 'Add element'), ['class' => 'btn btn-default', 'id' => 'addElement', 'data-template' => 'header']) ?>

<table id="layoutElements" class="table table-hover elementListView">
    <thead>
        <th><?= Yii::t('easyii/content', 'Options') ?></th>
        <th width="120"></th>
    </thead>
    <tbody>
    <?php foreach($model->elements as $element) : ?>
	    <?= $element->render($this) ?>
    <?php endforeach; ?>
    </tbody>
</table>