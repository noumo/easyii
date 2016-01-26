<?php
namespace yii\easyii\modules\content\api;

use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii\components\ApiObject;
use yii\easyii\models\Photo;
use yii\easyii\modules\content\models\Element;
use yii\easyii\modules\content\models\Item;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

class ElementObject extends ApiObject
{
	public $moduleName;
	public $functionName;

	/** @var ApiObject[] */
	public $data;

	public function render()
	{
		$dataProvider = new ActiveDataProvider($this->data);

		return ListView::widget([
			'dataProvider' => $dataProvider
		]);
	}

    public function getEditLink(){
        return Url::to(['/admin/content/element/edit/', 'id' => $this->id]);
    }

    public function getCreateLink(){
        return Html::a(Yii::t('easyii/content/api', 'Create page'), ['/admin/content/element/new'], ['target' => '_blank']);
    }
}