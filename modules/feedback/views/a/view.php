<?php
use yii\helpers\Html;
use yii\easyii\modules\feedback\models\Feedback;
use yii\widgets\ActiveForm;

$this->title = Yii::t('easyii/feedback', 'View feedback');
$this->registerCss('.feedback-view dt{margin-bottom: 10px;}');

if($model->status == Feedback::STATUS_ANSWERED) {
    $this->registerJs('$(".send-answer").click(function(){return confirm("'.Yii::t('easyii/feedback', 'Are you sure you want to resend the answer?').'");})');
}
?>
<?= $this->render('_menu', ['noanswer' => $model->status == Feedback::STATUS_ANSWERED]) ?>

<dl class="dl-horizontal feedback-view">
    <dt><?= Yii::t('easyii', 'Name') ?></dt>
    <dd><?= $model->name ?></dd>

    <dt>E-mail</dt>
    <dd><?= $model->email ?></dd>

    <?php if($this->context->module->settings['enablePhone']) : ?>
    <dt><?= Yii::t('easyii/feedback', 'Phone') ?></dt>
    <dd><?= $model->phone ?></dd>
    <?php endif; ?>

    <?php if($this->context->module->settings['enableTitle']) : ?>
    <dt><?= Yii::t('easyii', 'Title') ?></dt>
    <dd><?= $model->title ?></dd>
    <?php endif; ?>

    <dt>IP</dt>
    <dd><?= $model->ip ?> <a href="//freegeoip.net/?q=<?= $model->ip ?>" class="label label-info" target="_blank">info</a></dd>

    <dt><?= Yii::t('easyii', 'Date') ?></dt>
    <dd><?= Yii::$app->formatter->asDatetime($model->time, 'medium') ?></dd>

    <dt><?= Yii::t('easyii', 'Text') ?></dt>
    <dd><?= nl2br($model->text) ?></dd>
</dl>

<?php if($model->email) : ?>
    <hr>
    <h2><small><?= Yii::t('easyii/feedback', 'Answer') ?></small></h2>

    <?php $form = ActiveForm::begin() ?>
        <?= $form->field($model, 'answer_subject') ?>
        <?= $form->field($model, 'answer_text')->textarea(['style' => 'height: 250px']) ?>
        <?= Html::submitButton(Yii::t('easyii', 'Send'), ['class' => 'btn btn-success send-answer']) ?>
    <?php ActiveForm::end() ?>
<?php endif; ?>