<?php
/**
 * @var View $this
 */
use yii\easyii\widgets\Redactor;
use yii\helpers\Url;
use yii\web\View;

?>
<?php $sid = uniqid() ?>

<?= \yii\helpers\Html::activeLabel($this->context->model, $this->context->attribute); ?>

<ul class="nav nav-tabs" role="tablist">
    <?php foreach ($this->context->model->mlConfig['languages'] as $languageCode => $languageName): ?>

        <li class="<?= (Yii::$app->language == $languageCode) ? 'active' : '' ?>">
            <a href="#<?= $sid . $languageCode ?>" role="tab" data-toggle="tab">
                <?= $languageName ?>
            </a>
        </li>
    <?php endforeach ?>

</ul>

<div class="tab-content">

    <?php foreach ($this->context->model->mlConfig['languages'] as $languageCode => $languageName): ?>

        <?php
        $attribute = $this->context->attribute;

        if ( $languageCode != $this->context->model->mlConfig['default_language'] )
        {
            $attribute .= '_' . $languageCode;
        }

        $activeClass = (Yii::$app->language == $languageCode) ? 'active' : '';
        ?>


        <div class="tab-pane <?= $activeClass ?>" id="<?= $sid . $languageCode ?>">

            <?= Redactor::widget([
                'model' => $this->context->model,
                'attribute' => $attribute,
                'options' => $this->context->options
            ]); ?>
        </div>


    <?php endforeach ?>
</div>