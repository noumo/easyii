<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\easyii\widgets\Redactor;
?>
<?php $form = ActiveForm::begin([
    'enableAjaxValidation' => true,
    'options' => ['enctype' => 'multipart/form-data']
]); ?>
<?= $form->field($model, 'title') ?>
<?php if($this->context->module->settings['enableImage']) : ?>
    <?php if($model->image) : ?>
        <img src="<?= Yii::$app->request->baseUrl.$model->image ?>">
        <a href="/admin/news/a/clear-image/<?= $model->news_id ?>" class="text-danger confirm-delete" title="<?= Yii::t('easyii/news', 'Clear image')?>"><?= Yii::t('easyii/news', 'Clear image')?></a>
    <?php endif; ?>
    <?= $form->field($model, 'image')->fileInput() ?>
<?php endif; ?>
<?php if($this->context->module->settings['enableShort']) : ?>
    <?= $form->field($model, 'short')->textarea() ?>
<?php endif; ?>
<?= $form->field($model, 'text')->widget(Redactor::className(),[
    'options' => [
        'minHeight' => 400,
        'imageUpload' => '/admin/redactor/upload?dir=news',
        'fileUpload' => '/admin/redactor/upload?dir=news',
        'plugins' => ['fullscreen']
    ]
]) ?>
<?= Html::submitButton(Yii::t('easyii', 'Save'), ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>