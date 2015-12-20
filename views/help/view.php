<?php
/**
 * @var \yii\easyii\components\Module $module
 * @var string $readmeContent
 */
use kartik\markdown\Markdown;

?>

<?= Markdown::convert($readmeContent, [], Markdown::SMARTYPANTS_ATTR_DO_NOTHING); ?>
