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

	public function runTemplate()
	{
		$this->layout = 'contentElement';

		if (Yii::$app->request->isAjax) {
			$this->view->clear();
			$this->layout = 'contentElementAjax';
		}

		$id = $this->getId();

		$options = [
			'templateUrl' => Url::to(['/admin/content/contentElements/content-element/template']),
			'showModalSelector' => "#$id #addElement",
			'modalSelector' => "#$id #elementModal",
			'deleteElementSelector' => "#$id .delete-element",
			'addElementSelector' => "#$id [data-content-element]",
			'parentId' => $this->element->primaryKey
		];
		$options = Json::encode($options);
		$this->view->registerJs("$('#$id .elementListView').elementListView($options);", View::POS_READY);

		return $this->render('template');
	}
}