<?php

namespace yii\easyii\modules\content\contentElements\heading;

use yii\easyii\modules\content\contentElements\ContentElementWidget;
use yii\easyii\modules\content\contentElements\heading\models\HeadingElement;

class Widget extends ContentElementWidget
{
	function createElement()
	{
		return new HeadingElement();
	}

}