<?php

namespace yii\easyii\modules\content\contentElements\dynamic;

use yii\data\ArrayDataProvider;
use yii\easyii\modules\content\contentElements\ContentElementWidget;
use yii\easyii\modules\content\contentElements\dynamic\models\DynamicElement;

/**
 * Class Widget
 *
 * @property DynamicElement $element
 *
 * @author Bennet Klarhoelter <boehsermoe@me.com>
 */
class Widget extends ContentElementWidget
{
	public function createElement()
	{
		return new DynamicElement();
	}

	public function run($view = 'view')
	{
		$params = [];

		if ($view === 'view') {
			$data = $this->element->fetchData();

			if (is_array($data) ) {
				$view = 'listView';
				$dataProvider = new ArrayDataProvider();
				$dataProvider->allModels = $data;
				$data = $dataProvider;
			}
			else {
				$view = 'detailView';
			}

			$params['data'] = $data;
		}

		return $this->render($view, $params);
	}
}