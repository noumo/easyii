<?php
/**
 * @var \yii\web\View $this
 * @var \yii\easyii\components\Module $module
 * @var string $readmeContent
 */

$this->title = $module->title;

\yii\easyii\assets\HelpAsset::register($this);

?>

<?= \yii\helpers\Markdown::process($readmeContent, 'gfm'); ?>
