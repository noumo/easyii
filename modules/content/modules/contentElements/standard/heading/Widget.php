<?php

namespace yii\easyii\modules\content\contentElements\standard\heading;

use yii\easyii\modules\content\contentElements\ContentElementWidget;
use yii\easyii\modules\content\contentElements\standard\heading\models\Element;

class Widget extends ContentElementWidget
{
	function createElement()
	{
		return new Element();
	}

}