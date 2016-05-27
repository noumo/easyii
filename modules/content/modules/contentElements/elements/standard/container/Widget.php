<?php

namespace yii\easyii\modules\content\modules\contentElements\elements\standard\container;

use yii\easyii\modules\content\modules\contentElements\BaseWidget;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use Yii;

class Widget extends BaseWidget
{
	public static $autoIdPrefix = 'newContainer';

	public function init()
	{
		parent::init();

		if (!$this->element->isNewRecord) {
			$this->id = 'container' . $this->element->primaryKey;
		}
	}
}