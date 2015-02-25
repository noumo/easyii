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
    <?php if($model->thumb) : ?>
        <img src="<?= Yii::$app->request->baseUrl.$model->thumb ?>">
        <a href="/admin/article/a/clear-image/<?= $model->primaryKey ?>" class="text-danger confirm-delete" title="<?= Yii::t('easyii/article', 'Clear image')?>"><?= Yii::t('easyii/article', 'Clear image')?></a>
    <?php endif; ?>
    <?= $form->field($model, 'thumb')->fileInput() ?>
<?php endif; ?>

<?php if(IS_ROOT) : ?>
    <?= $form->field($model, 'slug') ?>
    <?= SeoForm::widget(['model' => $model]) ?>
<?php endif; ?>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>