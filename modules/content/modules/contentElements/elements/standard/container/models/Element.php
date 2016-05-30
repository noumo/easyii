<?php

namespace yii\easyii\modules\content\modules\contentElements\elements\standard\container\models;

use yii\easyii\modules\content\modules\contentElements\ContentElementModule;
use yii\easyii\modules\content\modules\contentElements\models\BaseElement;
use yii\web\View;

class Element extends BaseElement
{
	public $collapsible = false;

	public function render(View $view)
	{
		$widget = ContentElementModule::createWidget($this);

		return $widget->runTemplate();
	}
}