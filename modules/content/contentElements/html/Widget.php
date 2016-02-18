<?php

namespace yii\easyii\modules\content\contentElements\html;

use yii\easyii\modules\content\contentElements\ContentElementWidget;
use yii\easyii\modules\content\contentElements\html\models\HtmlElement;

class Widget extends ContentElementWidget
{
	function createElement()
	{
		return new HtmlElement();
	}

}