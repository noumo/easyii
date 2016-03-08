<?php
/**
 * @var \yii\easyii\modules\content\models\Item $model
 */
use yii\easyii\helpers\Image;
use yii\easyii\widgets\DateTimePicker;
use yii\easyii\widgets\Redactor;
use yii\easyii\widgets\SeoForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\easyii\modules\content\models\Item;

$settings = $this->context->module->settings;
$module = $this->context->module->id;
$categories = \yii\helpers\ArrayHelper::map(\yii\easyii\modules\content\api\Content::tree(), 'category_id', 'title');
?>

<?php $form = ActiveForm::begin(
	[
		'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
	]
); ?>

<?= $form->field($model, 'title') ?>
<?= $form->field($model, 'header') ?>

	<div class="row">
		<div class="col-md-6">
			<?php if ($settings['itemThumb']) : ?>
				<?php if ($model->image_file) : ?>
					<img src="<?= Image::thumb($model->image_file, 240) ?>">
					<a href="<?= Url::to(['/admin/' . $module . '/item/clear-image', 'id' => $model->primaryKey]) ?>"
					   class="text-danger confirm-delete"
					   title="<?= Yii::t('easyii', 'Clear image') ?>"><?= Yii::t('easyii', 'Clear image') ?></a>
				<?php endif; ?>
				<?= $form->field($model, 'image_file')->fileInput() ?>
			<?php endif; ?>
		</div>
		<?php if (IS_ROOT) : ?>
			<div class="col-md-3">
				<?= $form->field($model, 'category_id')->dropDownList($categories, ['prompt' => 'Default']) ?>
			</div>
		<?php endif; ?>
	</div>

<?php
// Todo: Dont need layout fields more?
#$dataForm ?>

<ol class="sortable"><?= $model->element->render($this) ?></ol>

<?= $form->field($model, 'time')->widget(DateTimePicker::className()); ?>

<?php if (IS_ROOT) : ?>
	<?= $form->field($model, 'slug') ?>
	<?= SeoForm::widget(['model' => $model]) ?>
<?php endif; ?>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>