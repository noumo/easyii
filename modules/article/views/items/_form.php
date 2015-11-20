<?php
use yii\easyii\helpers\Image;
use yii\easyii\modules\article\models\Category;
use yii\easyii\widgets\DateTimePicker;
use yii\easyii\widgets\TagsInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\easyii\widgets\SeoForm;

$module = $this->context->module->id;
?>
<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
]); ?>
<?= $form->field($model, 'title') ?>
<?= $form->field($model, 'category_id')->dropDownList($cats) ?>

<?php if($this->context->module->settings['articleThumb']) : ?>
    <?php if($model->image_file) : ?>
        <a href="<?= $model->image ?>" class="fancybox"><img src="<?= Image::thumb($model->image_file, 240, 180) ?>"></a>
        <a href="<?= Url::to(['/admin/'.$module.'/items/clear-image', 'id' => $model->primaryKey]) ?>" class="text-danger confirm-delete" title="<?= Yii::t('easyii', 'Clear image')?>"><?= Yii::t('easyii', 'Clear image')?></a>
    <?php endif; ?>
    <?= $form->field($model, 'image_file')->fileInput() ?>
<?php endif; ?>

<?php if($this->context->module->settings['enableShort']) : ?>
    <?= $form->field($model, 'short')->textarea() ?>
<?php endif; ?>

<?= $form->field($model, 'text')->widget(\yii\easyii\widgets\Redactor::className()) ?>

<?= $form->field($model, 'time')->widget(DateTimePicker::className()); ?>

<?php if($this->context->module->settings['enableTags']) : ?>
    <?= $form->field($model, 'tagNames')->widget(TagsInput::className()) ?>
<?php endif; ?>

<?php if(IS_ROOT) : ?>
    <?= $form->field($model, 'slug') ?>
    <?= SeoForm::widget(['model' => $model]) ?>
<?php endif; ?>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>