<?php
use yii\easyii\helpers\Image;
use yii\easyii\models\Setting;
use yii\easyii\widgets\DateTimePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\easyii\widgets\SeoForm;

$settings = $this->context->module->settings;
$module = $this->context->module->id;
?>

<?php $form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'model-form']
]); ?>
<?= $form->field($model, 'title') ?>
<?php if($settings['itemThumb']) : ?>
    <?php if($model->image_file) : ?>
        <a href="<?= $model->image ?>" class="fancybox"><img src="<?= Image::thumb($model->image_file, 240, 180) ?>"></a>
        <a href="<?= Url::to(['/admin/'.$module.'/items/clear-image', 'id' => $model->primaryKey]) ?>" class="text-danger confirm-delete" title="<?= Yii::t('easyii', 'Clear image')?>"><?= Yii::t('easyii', 'Clear image')?></a>
    <?php endif; ?>
    <?= $form->field($model, 'image_file')->fileInput() ?>
<?php endif; ?>
<?= $dataForm ?>
<?php if($settings['itemDescription']) : ?>
    <?= $form->field($model, 'description')->widget(\vova07\imperavi\Widget::className(), [
        'settings' => [
            'lang' => \yii\easyii\helpers\Data::getLocale(),
            'minHeight' => 400,
            'imageUpload' => Url::to(['/admin/redactor/image-upload']),
            'fileUpload' => Url::to(['/admin/redactor/file-upload']),
            'imageManagerJson' => Url::to(['/admin/redactor/images-get']),
            'fileManagerJson' => Url::to(['/admin/redactor/files-get']),
            'plugins' => Setting::getAsArray('redactor_plugins')
        ]
    ]) ?>
<?php endif; ?>

<?= $form->field($model, 'available') ?>
<?= $form->field($model, 'price') ?>
<?= $form->field($model, 'discount') ?>

<?= $form->field($model, 'time')->widget(DateTimePicker::className()); ?>

<?php if(IS_ROOT) : ?>
    <?= $form->field($model, 'slug') ?>
    <?= SeoForm::widget(['model' => $model]) ?>
<?php endif; ?>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>