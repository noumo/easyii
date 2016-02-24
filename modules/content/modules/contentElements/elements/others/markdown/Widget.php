<?php

namespace yii\easyii\modules\content\modules\contentElements\elements\others\markdown;

use yii\easyii\modules\content\modules\contentElements\BaseWidget;
use yii\easyii\modules\content\modules\contentElements\elements\standard\heading\models\Element;

class Widget extends BaseWidget
{
	public function createElement()
	{
		return new Element();
	}

}