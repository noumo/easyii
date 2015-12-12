<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\easyii\modules\content\models\Item;

$this->title = Yii::t('easyii/content', 'Create item');
?>
<?= $this->render('_menu', ['layout' => $layout]) ?>

<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
]); ?>
<?= $form->field($model, 'title') ?>
<?= $form->field($model, 'category_id')->dropDownList($categories) ?>

<?php if(!empty($parent)) : ?>
	<div class="form-group field-category-title required">
		<label for="category-parent" class="control-label"><?= Yii::t('easyii', 'Parent category') ?></label>
		<select class="form-control" id="category-parent" name="parent">
			<option value="" class="smooth"><?= Yii::t('easyii', 'No') ?></option>
			<?php foreach(Item::find()->sort()->asArray()->all() as $node) : ?>
				<option
					value="<?= $node['item_id'] ?>"
					<?php if($parent == $node['item_id']) echo 'SELECTED' ?>
					style="padding-left: <?= $node['depth']*20 ?>px;"
					><?= $node['title'] ?></option>
			<?php endforeach; ?>
		</select>
	</div>
<?php endif; ?>

<?php if (IS_ROOT) : ?>
	<?= $form->field($model, 'slug') ?>
<?php endif; ?>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>
