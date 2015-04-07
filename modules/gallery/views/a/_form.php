<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\easyii\widgets\SeoForm;
?>
<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['enctype' => 'multipart/form-data']
]); ?>
<?= $form->field($model, 'title') ?>
<?php if($this->context->module->settings['albumThumb']) : ?>
    <?php if($model->thumb) : ?>
        <img src="<?= $model->thumb ?>">
        <a href="<?= Url::to(['/admin/gallery/a/clear-image', 'id' => $model->primaryKey]) ?>" class="text-danger confirm-delete" title="<?= Yii::t('easyii/gallery', 'Clear image')?>"><?= Yii::t('easyii/gallery', 'Clear image')?></a>
    <?php endif; ?>
    <?= $form->field($model, 'thumb')->fileInput() ?>
<?php endif; ?>

<?php if(IS_ROOT) : ?>
    <?= $form->field($model, 'slug') ?>
    <?= SeoForm::widget(['model' => $model]) ?>
<?php endif; ?>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>