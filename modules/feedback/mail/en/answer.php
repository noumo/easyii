<?php

use yii\helpers\ArrayHelper;

$this->title = $subject;
?>
<p><?= $html_answer ?></p>
<br/>
<br/>
<hr>
<p><?= $nice_date ?> you wrote:</p>
<p>
    <?php foreach(explode("\n", ArrayHelper::getValue($feedback, 'text')) as $line) echo '> '.$line.'<br/>'; ?>
</p>