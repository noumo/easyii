<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\easyii\widgets\SeoForm;

$settings = $this->context->module->settings;
?>
<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['enctype' => 'multipart/form-data']
]); ?>
<?= $form->field($model, 'title') ?>
<?php if($settings['categoryThumb']) : ?>
    <?php if($model->image) : ?>
        <img src="<?= Yii::$app->request->baseUrl.$model->image ?>">
        <a href="/admin/catalog/a/clear-image/<?= $model->primaryKey ?>" class="text-danger confirm-delete" title="<?= Yii::t('easyii/catalog', 'Clear image')?>"><?= Yii::t('easyii/catalog', 'Clear image')?></a>
    <?php endif; ?>
    <?= $form->field($model, 'image')->fileInput() ?>
<?php endif; ?>

<?php if(IS_ROOT) : ?>
    <?= $form->field($model, 'slug') ?>
    <?= SeoForm::widget(['model' => $model]) ?>
<?php endif; ?>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>