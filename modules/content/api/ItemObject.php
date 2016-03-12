<?php
namespace yii\easyii\modules\content\api;

use Yii;
use yii\easyii\components\ApiObject;
use yii\easyii\models\Photo;
use yii\easyii\modules\content\models\Item;
use yii\easyii\modules\content\modules\contentElements\BaseElement;
use yii\easyii\modules\content\modules\contentElements\BaseWidget;
use yii\easyii\modules\content\modules\contentElements\ContentElementModule;
use yii\easyii\modules\content\modules\contentElements\Factory;
use yii\helpers\Html;
use yii\helpers\Url;

class ItemObject extends ApiObject
{
    public $slug;
    public $image;
	/** @var array */
    public $data;
	/** @var array */
	public $elements;
	public $category_id;
	public $element_id;
	public $nav;

	public $time;
	public $tree;
	public $depth;

	public $order_num;

	/** @var  ItemObject[] */
	public $children;

	private $_photos;
	private $_contentElements;

	public function getTitle(){
        if($this->model->isNewRecord){
            return $this->createLink;
        }

        $value = $this->placeholder($this->model->title);

        return $this->liveEdit($value);
    }

    public function getContent(){
        if($this->model->isNewRecord){
            return $this->createLink;
        }

        $value = $this->placeholder($this->model->content);

        return $this->liveEdit($value);
    }

    public function getHeader(){
        if($this->model->isNewRecord){
            return $this->createLink;
        }

        $value = $this->placeholder($this->model->header);

        return $this->liveEdit($value);
    }

    /**
     * @return LayoutObject
     */
	public function getLayout()
	{
		return Content::cat($this->category_id);
	}

	public function getDate()
	{
		return Yii::$app->formatter->asDate($this->time);
	}

    public function getPhotos()
    {
        if(!$this->_photos){
            $this->_photos = [];

            foreach(Photo::find()->where(['class' => Item::className(), 'item_id' => $this->id])->sort()->all() as $model){
                $this->_photos[] = new PhotoObject($model);
            }
        }
        return $this->_photos;
    }

	/**
	 * @return BaseWidget[]
	 */
	public function getContentElements()
	{
		if(!$this->_contentElements){
			$this->_contentElements = [];

			foreach(BaseElement::find()->where(['element_id' => $this->element_id])->sort(SORT_ASC)->all() as $model) {
				$this->_contentElements[] = ContentElementModule::createWidget($model);
			}
		}

		return $this->_contentElements;
	}

	public function getChildren($where = null)
	{
		if (!$this->children)
		{
			$this->children = [];
			foreach ($this->model->children(1)->andWhere($where)->all() as $child)
			{
				$this->children[] = new ItemObject($child);
			}
		}

		return $this->children;
	}

	public function render()
	{
		if ($this->model->isNewRecord) {
			return $this->createLink;
		}

		$output = '';

		foreach ($this->getContentElements() as $element) {
			$output .= $element->run();
		}

		$output = $this->placeholder($output);

		return $this->liveEdit($output);
	}

    public function getEditLink(){
        return Url::to(['/admin/content/item/edit/', 'id' => $this->id]);
    }

    public function getCreateLink(){
        return Html::a(Yii::t('easyii/content/api', 'Create page'), ['/admin/content/item/new'], ['target' => '_blank']);
    }
}