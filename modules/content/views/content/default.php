<?php
/** 
 * @var \yii\web\View $this
 * @var \yii\easyii\modules\content\api\ItemObject $content
 */
?>

<div class="container">

	<div class="col-md-12 text-center">
		<h1><?= $content->getHeader() ?></h1>

		<?= $content->getContent() ?>

	</div>
</div>

