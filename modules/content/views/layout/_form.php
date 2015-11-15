<?php
use yii\easyii\helpers\Image;
use yii\easyii\widgets\TagsInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use \yii\easyii\modules\content\models\Layout;

$settings = $this->context->module->settings;
?>
<?php $form = ActiveForm::begin([
	'enableAjaxValidation' => true,
	'options' => ['enctype' => 'multipart/form-data']
]); ?>
<?= $form->field($model, 'title') ?>

<?php if(!empty($settings['categoryThumb'])) : ?>
	<?php if($model->image_file) : ?>
		<a href="<?= $model->image ?>" class="fancybox"><img src="<?= Image::thumb($model->image_file, 240, 180) ?>"></a>
		<a href="<?= Url::to(['/clear-image', 'id' => $model->primaryKey]) ?>" class="text-danger confirm-delete" title="<?= Yii::t('easyii', 'Clear image')?>"><?= Yii::t('easyii', 'Clear image')?></a>
	<?php endif; ?>
	<?= $form->field($model, 'image_file')->fileInput() ?>
<?php endif; ?>

<?php if(IS_ROOT) : ?>
	<?= $form->field($model, 'slug') ?>
	<?php if(isset($model->attributes['cache'])) : ?>
		<?= $form->field($model, 'cache')->checkbox() ?>
	<?php endif; ?>
<?php endif; ?>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>