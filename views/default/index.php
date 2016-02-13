<?php
use yii\easyii\models\Setting;
use yii\helpers\Url;

$this->title = Yii::t('easyii', 'Welcome');
?>
<?php if(count($notifications)) : ?>
    <h2><?= Yii::t('easyii', 'New on website') ?></h2>
    <div class="row welcome-notifications">
        <?php foreach($notifications as $i => $module) : ?>
            <div class="col-md-3">
                <a href="<?= Url::to(['/admin/' . $module->name]) ?>" class="module-link module-link-color<?= ++$i ?>">
                    <i class="glyphicon glyphicon-<?= $module->icon ?>"></i>
                    <div class="pull-right text-right">
                        <div class="module-title"><?= $module->title ?></div>
                        <div class="module-notices"><?= $module->notice ?></div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    <hr class="margin-30"/>
<?php endif; ?>
<?php if(Setting::get('ga_service_email') && Setting::get('ga_profile_id') && Setting::get('ga_p12_file')) : ?>
    <?= \yii\easyii\widgets\GoogleCharts::widget([
        'email' => Setting::get('ga_service_email'),
        'ids' => Setting::get('ga_profile_id'),
        'p12' => Setting::get('ga_p12_file')
    ]) ?>
<?php else : ?>
    <p><?= Yii::t('easyii', 'Welcome to control panel, choose which section you want to manage in left menu.') ?></p>
<?php endif; ?>
