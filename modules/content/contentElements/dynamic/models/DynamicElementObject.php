<?php

namespace yii\easyii\modules\content\contentElements\dynamic\models;

use yii\data\ArrayDataProvider;
use yii\easyii\components\ApiObject;
use yii\easyii\modules\content\contentElements\ContentElementFactory;
use yii\widgets\ListView;

class DynamicElementObject extends ContentElementFactory
{
	/** @var ApiObject[] */
	private $_data;

	public function render()
	{
		$dataProvider = new ArrayDataProvider();
		$dataProvider->allModels = $this->getData();

		return ListView::widget([
			'dataProvider' => $dataProvider,
			'itemView' => '@easyii/modules/content/views/content-element/_itemView'
		]);
	}

	public function getData()
	{
		if (!$this->_data) {
			$this->_data = $this->fetchData();
		}

		return $this->_data;
	}

	/**
	 * @return ApiObject|ApiObject[]
	 */
	public function fetchData()
	{
		/** @var \yii\easyii\components\API $moduleName */
		$moduleName = $this->module;
		$functionName = $this->function;

		/** @var ApiObject|ApiObject[] $result */
		$result = $moduleName::$functionName();

		return $result;
	}
}