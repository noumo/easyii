<?php

namespace yii\easyii\modules\content\modules\contentElements\elements\standard\container\models;

use yii\easyii\modules\content\modules\contentElements\BaseElement;
use yii\easyii\modules\content\modules\contentElements\ContentElementModule;
use yii\web\View;

class Element extends BaseElement
{
	public function render(View $view)
	{
		$widget = ContentElementModule::createWidget($this);

		return $widget->runTemplate();
	}
}