<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = $subject;
?>
<p>User <b><?= ArrayHelper::getValue($feedback, 'name') ?></b> left a message in your guestbook.</p>
<p>You can view it <?= Html::a('here', $link) ?>.</p>