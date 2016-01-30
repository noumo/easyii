<?php

namespace yii\easyii\modules\content\contentElements;

use yii\base\Widget;
use yii\bootstrap\Html;
use yii\helpers\Url;

abstract class ContentElementWidget extends Widget
{
	protected $model;

	public function __construct(ContentElementBase $model, array $config = null)
	{
		parent::__construct($config);

		$this->model = $model;
	}

	public function run($view = 'view')
	{
		$content = $this->render($view, ['model' => $this->model]);

		return $content;
	}

	public function getEditLink()
	{
		return Url::to(['/admin/content/element/edit/', 'id' => $this->id]);
	}

	public function getCreateLink()
	{
		return Html::a(\Yii::t('easyii/content/api', 'Create page'),
			['/admin/content/element/new'],
			['target' => '_blank']);
	}
}