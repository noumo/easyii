<?php

namespace yii\easyii\modules\content\modules\contentElements\elements\others\module;

use yii\data\ArrayDataProvider;
use yii\easyii\modules\content\modules\contentElements\BaseWidget;
use yii\easyii\modules\content\modules\contentElements\elements\dynamic\models\Element;

/**
 * Class Widget
 *
 * @property Element $element
 *
 * @author Bennet Klarhoelter <boehsermoe@me.com>
 */
class Widget extends BaseWidget
{
	public function createElement()
	{
		return new Element();
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