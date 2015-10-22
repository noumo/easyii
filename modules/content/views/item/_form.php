<?php
use yii\easyii\helpers\Image;
use yii\easyii\widgets\DateTimePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\easyii\widgets\Redactor;
use yii\easyii\widgets\SeoForm;

$settings = $this->context->module->settings;
$module = $this->context->module->id;
?>

<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
]); ?>
<?= $form->field($model, 'title') ?>
<?= $form->field($model, 'header') ?>

<?php if($settings['itemThumb']) : ?>
    <?php if($model->image_file) : ?>
        <img src="<?= Image::thumb($model->image_file, 240) ?>">
        <a href="<?= Url::to(['/admin/'.$module.'/item/clear-image', 'id' => $model->primaryKey]) ?>" class="text-danger confirm-delete" title="<?= Yii::t('easyii', 'Clear image')?>"><?= Yii::t('easyii', 'Clear image')?></a>
    <?php endif; ?>
    <?= $form->field($model, 'image_file')->fileInput() ?>
<?php endif; ?>
<?= $dataForm ?>

<?= $form->field($model, 'content')->widget(Redactor::className(),[
    'options' => [
        'minHeight' => 400,
        'imageUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'content'], true),
        'fileUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'content'], true),
        'plugins' => ['fullscreen']
    ]
]) ?>


<?= $form->field($model, 'time')->widget(DateTimePicker::className()); ?>

<?php if(IS_ROOT) : ?>
    <?= $form->field($model, 'slug') ?>
    <?= SeoForm::widget(['model' => $model]) ?>
<?php endif; ?>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>