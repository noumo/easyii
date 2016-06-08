<?php

namespace yii\easyii\modules\content\modules\contentElements\elements\standard\container\models;

use yii\easyii\modules\content\modules\contentElements\ContentElementModule;
use yii\easyii\modules\content\modules\contentElements\models\BaseElement;
use yii\easyii\modules\content\modules\contentElements\models\ElementOption;
use yii\web\View;

class Element extends BaseElement
{
	public $collapsible = false;

	public function render()
	{
		$widget = ContentElementModule::createWidget($this);

		return $widget->runTemplate();
	}

	public function defaultOptions()
	{
		$options = [
			ElementOption::create(ElementOption::TYPE_ID),
			ElementOption::create(ElementOption::TYPE_CLASS),
			ElementOption::create(ElementOption::TYPE_STYLE),
			ElementOption::create(ElementOption::TYPE_TAG),
		];

		$this->addOptionsAfterSave($options);
	}
}