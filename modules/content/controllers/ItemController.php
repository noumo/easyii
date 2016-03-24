<?php
namespace yii\easyii\modules\content\controllers;

use Yii;
use yii\base\Exception;
use yii\easyii\actions\ChangeStatusAction;
use yii\easyii\actions\ClearImageAction;
use yii\easyii\actions\MoveAction;
use yii\easyii\actions\SortAction;
use yii\easyii\behaviors\SortableModel;
use yii\easyii\components\Controller;
use yii\easyii\helpers\Image;
use yii\easyii\modules\content\api\Content;
use yii\easyii\modules\content\modules\contentElements\models\BaseElement;
use yii\easyii\modules\content\modules\contentElements\ContentElementModule;
use yii\easyii\modules\content\modules\contentElements\Factory;
use yii\easyii\modules\content\models\Item;
use yii\easyii\modules\content\models\Layout;
use yii\easyii\widgets\Redactor;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

class ItemController extends Controller
{
    public $categoryClass = 'yii\easyii\modules\content\models\Item';

    public function actions()
    {
        $className = Item::className();
        return [
            'clear-image' => [
                'class' => ClearImageAction::className(),
                'model' => $className
            ],
	        'up' => [
		        'class' => MoveAction::className(),
		        'model' => $className,
		        'direction' => 'up'
	        ],
	        'down' => [
		        'class' => MoveAction::className(),
		        'model' => $className,
		        'direction' => 'down'
	        ],
            'on' => [
                'class' => ChangeStatusAction::className(),
                'model' => $className,
                'status' => Item::STATUS_ON
            ],
            'off' => [
                'class' => ChangeStatusAction::className(),
                'model' => $className,
                'status' => Item::STATUS_OFF
            ],
        ];
    }

    public function actionIndex($id)
    {
        if(!($model = Layout::findOne($id))){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }

    public function actionAll()
    {
	    $items = Item::items();

        return $this->render('all', [
            'items' => $items
        ]);
    }

    public function actionNew($parent = null)
    {
        $model = new Item;

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else {
	            $parent = (int)Yii::$app->request->post('parent', null);
	            if($parent > 0 && ($parentCategory = Item::findOne($parent))){
		            $model->order_num = $parentCategory->order_num;
		            $model->appendTo($parentCategory);
	            } else {
		            $model->attachBehavior('sortable', SortableModel::className());
		            $model->makeRoot();
	            }

                if ($model->save()) {
	                $this->saveElements($model, Yii::$app->request->post('Element'));

	                $this->flash('success', Yii::t('easyii/content', 'Item created'));
                    return $this->redirect(['/admin/'.$this->module->id.'/item/edit/', 'id' => $model->primaryKey]);
                } else {
                    $this->flash('error', Yii::t('easyii', 'Create error. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        }
        else {
            $categories = ArrayHelper::map(Content::tree(), 'category_id', 'title');

            return $this->render('new', [
                'model' => $model,
                'categories' => $categories,
	            'parent' => $parent,
            ]);
        }
    }

    public function actionEdit($id)
    {
        /** @var Item $model */
        if(!($model = Item::findOne($id))){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else {

				$transaction = Yii::$app->db->beginTransaction();

               try {
				   $saved = $this->saveElements($model, Yii::$app->request->post('Element'));

				   if ($model->save() && $saved) {
					   $transaction->commit();

					   $this->flash('success', Yii::t('easyii/content', 'Item updated'));
					   $this->refresh();
				   }
				   else {
					   $transaction->rollBack();
					   $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
				   }
			   }
			   catch (Exception $ex) {
				   $transaction->rollBack();
				   throw $ex;
			   }
            }
        }

	    return $this->render('edit', [
		    'model' => $model,
		    'dataForm' => $this->generateForm($model->layout->fields, $model->data)
	    ]);
    }

	private function generateForm($fields, $data = null)
	{
		$result = '';

		if (empty($fields)) {
			return $result;
		}

		foreach($fields as $field)
		{
			$value = !empty($data->{$field->name}) ? $data->{$field->name} : null;
			if ($field->type === 'string') {
				$result .= '<div class="form-group"><label>'. $field->title .'</label>'. Html::input('text', "Data[{$field->name}]", $value, ['class' => 'form-control']) .'</div>';
			}
			elseif ($field->type === 'text') {
				$result .= '<div class="form-group"><label>'. $field->title .'</label>'. Html::textarea("Data[{$field->name}]", $value, ['class' => 'form-control']) .'</div>';
			}
			elseif ($field->type === 'html') {
				$result .= Html::beginTag('div', ['class' => 'form-group']);
				$result .= Html::label($field->title);
				$result .= Redactor::widget([
						'name' => "Data[{$field->name}]",
						'value' => $value,
						'settings' => [
							'minHeight' => 100,
						],
						'options' => [
							'class' => 'form-control',
							'minHeight' => 100,
							'imageUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'content'], true),
							'fileUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'content'], true),
							'plugins' => ['fullscreen']
						]
					]
				);
				$result .= Html::endTag('div');
			}
			elseif ($field->type === 'boolean') {
				$result .= '<div class="checkbox"><label>'. Html::checkbox("Data[{$field->name}]", $value, ['uncheck' => 0]) .' '. $field->title .'</label></div>';
			}
			elseif ($field->type === 'select') {
				$options = ['' => Yii::t('easyii/content', 'Select')];
				foreach($field->options as $option){
					$options[$option] = $option;
				}
				$result .= '<div class="form-group"><label>'. $field->title .'</label><select name="Data['.$field->name.']" class="form-control">'. Html::renderSelectOptions($value, $options) .'</select></div>';
			}
			elseif ($field->type === 'checkbox') {
				$options = '';
				foreach($field->options as $option){
					$checked = $value && in_array($option, $value);
					$options .= '<br><label>'. Html::checkbox("Data[{$field->name}][]", $checked, ['value' => $option]) .' '. $option .'</label>';
				}
				$result .= '<div class="checkbox well well-sm"><b>'. $field->title .'</b>'. $options .'</div>';
			}
		}

		return $result;
	}

	/**
	 * @param $model
	 */
	protected function saveElements(Item $model, $data)
	{
		if (!$model->element) {
			$element = ContentElementModule::create('standard\container');
			$element->save();

			$model->link('element', $element);
		}

		if (empty($data)) {
			return true;
		}

		$error = false;
		$elements = [];

		$sortOrder = 1;
		foreach ($data as $elementKey => $attributes) {
			/** @var BaseElement|false $element */
			$element = false;

			if ($attributes['scenario'] == 'delete') {
				BaseElement::deleteAll(['element_id' => $attributes['element_id']]);
			}
			else {
				$attributes['order_num'] = $sortOrder++;
				$attributes['item_id'] = $model->primaryKey;

				if ($attributes['scenario'] == 'insert') {
					$widget = ContentElementModule::createWidgetByType($attributes['type']);
					$element = $widget->element;
					$widget->load($attributes);
					$widget->save();
				}
				elseif ($attributes['scenario'] == 'update') {
					$element = BaseElement::findOne(['element_id' => $attributes['element_id']]);
					$widget = ContentElementModule::createWidget($element);
					$widget->load($attributes);
					$widget->save();
				}
			}

			if ($element) {
				if ($element->hasErrors()) {
					$error = true;
				}

				$elements[] = $element;
			}
		}

		$model->populateRelation('elements', $elements);

		return !$error;
	}

	public function actionPhotos($id)
    {
        if(!($model = Item::findOne($id))){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        return $this->render('photos', [
            'model' => $model,
        ]);
    }

	public function actionDelete($id)
    {
        $model = Item::findOne($id);
        $children = $model->children()->all();
        $model->deleteWithChildren();
        foreach ($children as $child) {
            $child->afterDelete();
        }

        return $this->formatResponse(Yii::t('easyii/content', 'Item deleted'));
    }

	public function actionUp($id, $category_id)
    {
        return $this->move($id, 'up', ['category_id' => $category_id]);
    }

	public function actionDown($id, $category_id)
    {
        return $this->move($id, 'down', ['category_id' => $category_id]);
    }

	public function actionOn($id)
    {
        return $this->changeStatus($id, Item::STATUS_ON);
    }

	public function actionOff($id)
    {
        return $this->changeStatus($id, Item::STATUS_OFF);
    }
}