<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\easyii\widgets\Redactor;
use yii\easyii\widgets\SeoForm;
?>
<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['enctype' => 'multipart/form-data']
]); ?>
<?= $form->field($model, 'title') ?>

<?php if($this->context->module->settings['articleThumb']) : ?>
    <?php if($model->image) : ?>
        <img src="<?= Yii::$app->request->baseUrl.$model->image ?>">
        <a href="/admin/article/items/clear-image/<?= $model->primaryKey ?>" class="text-danger confirm-delete" title="<?= Yii::t('easyii/article', 'Clear image')?>"><?= Yii::t('easyii/article', 'Clear image')?></a>
    <?php endif; ?>
    <?= $form->field($model, 'image')->fileInput() ?>
<?php endif; ?>

<?php if($this->context->module->settings['enableShort']) : ?>
    <?= $form->field($model, 'short')->textarea() ?>
<?php endif; ?>

<?= $form->field($model, 'text')->widget(Redactor::className(),[
    'options' => [
        'minHeight' => 400,
        'imageUpload' => '/admin/redactor/upload?dir=article',
        'fileUpload' => '/admin/redactor/upload?dir=article',
        'plugins' => ['fullscreen']
    ]
]) ?>

<?php if(IS_ROOT) : ?>
    <?= $form->field($model, 'slug') ?>
    <?= SeoForm::widget(['model' => $model]) ?>
<?php endif; ?>

<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>