<?php
/**
 * @var \yii\web\View $this
 * @var \yii\easyii\components\Module $module
 * @var string $readmeContent
 */
use kartik\markdown\Markdown;

$this->title = $module->title;

\yii\easyii\assets\HelpAsset::register($this);

?>

<?= Markdown::convert($readmeContent, [], Markdown::SMARTYPANTS_ATTR_DO_NOTHING); ?>
