<?php

namespace yii\easyii\modules\content\modules\contentElements\elements\standard\texteditor;

use yii\easyii\modules\content\modules\contentElements\BaseWidget;
use yii\easyii\modules\content\modules\contentElements\elements\standard\texteditor\models\Element;

class Widget extends BaseWidget
{
	public function createElement()
	{
		return new Element();
	}
}