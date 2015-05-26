<?php
/** @todo input multilanguage widgets */
use webvimark\behaviors\multilanguage\input_widget\MultiLanguageActiveField;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\BootstrapPluginAsset;

BootstrapPluginAsset::register($this);

$labelOptions = ['class' => 'control-label'];
$inputOptions = ['class' => 'form-control'];
?>
<p>
    <a class="dashed-link collapsed" data-toggle="collapse" href="#seo-form" aria-expanded="false" aria-controls="seo-form"><?= Yii::t('easyii', 'Seo texts')?></a>
</p>

<?php $form = ActiveForm::begin(); ?>
<div class="collapse" id="seo-form">
    <div class="form-group">
        <?= $form->field($model, 'h1')->widget(MultiLanguageActiveField::className()); ?>
    </div>
    <div class="form-group">
        <?= $form->field($model, 'h1')->widget(MultiLanguageActiveField::className()); ?>
    </div>
    <div class="form-group">
        <?= $form->field($model, 'h1')->widget(MultiLanguageActiveField::className()); ?>
    </div>
    <div class="form-group">
        <?= $form->field($model, 'h1')->textarea()->widget(MultiLanguageActiveField::className(), ['inputType' => 'textArea']); ?>
    </div>
</div>
<?php $form = ActiveForm::end(); ?>