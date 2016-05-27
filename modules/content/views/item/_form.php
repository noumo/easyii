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

<?php if (IS_ROOT) : ?>

	<div class="row">
			<div class="col-md-3">
				<?= $form->field($model, 'category_id')->dropDownList($categories) ?>
			</div>

			<div class="col-md-3">
				<?= $form->field($model, 'slug') ?>
			</div>
	</div>

	<div>
		<?= SeoForm::widget(['model' => $model]) ?>
	</div>
<?php endif; ?>


<?php
// Todo: Dont need layout fields more?
#$dataForm ?>

<?= $model->element->renderAsRoot($this) ?>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary pull-right']) ?>
<?php ActiveForm::end(); ?>