<?php
namespace yii\easyii\modules\content\controllers;

use Yii;
use yii\easyii\actions\ChangeStatusAction;
use yii\easyii\actions\ClearImageAction;
use yii\easyii\actions\DeleteAction;
use yii\easyii\actions\MoveAction;
use yii\easyii\actions\SortAction;
use yii\easyii\behaviors\SortableModel;
use yii\easyii\components\Controller;
use yii\easyii\helpers\Image;
use yii\easyii\modules\content\api\Content;
use yii\easyii\modules\content\api\ItemObject;
use yii\easyii\modules\content\models\Item;
use yii\easyii\modules\content\models\Layout;
use yii\easyii\widgets\Redactor;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

class ItemController extends Controller
{
    public function actions()
    {
        $className = Item::className();
        return [
            'delete' => [
                'class' => DeleteAction::className(),
                'model' => $className,
                'successMessage' => Yii::t('easyii/content', 'Item deleted')
            ],
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
        if(!($items = Item::items())){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

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
        if(!($model = Item::findOne($id))){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else {
                $model->data = Yii::$app->request->post('Data');

                if (isset($_FILES) && $this->module->settings['itemThumb']) {

                    $model->image_file = UploadedFile::getInstance($model, 'image_file');
                    if ($model->image_file && $model->validate(['image_file'])) {
                        $model->image_file = Image::upload($model->image_file, 'content');
                    } else {
                        $model->image_file = $model->oldAttributes['image_file'];
                    }
                }

                if ($model->save()) {
                    $this->flash('success', Yii::t('easyii/content', 'Item updated'));
                    return $this->redirect(['/admin/'.$this->module->id.'/item/edit', 'id' => $model->primaryKey]);
                } else {
                    $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        }
        else {
            return $this->render('edit', [
                'model' => $model,
                'dataForm' => $this->generateForm($model->layout->fields, $model->data)
            ]);
        }
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

    public function actionClearImage($id)
    {
        $model = Item::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('easyii', 'Not found'));
        }
        elseif($model->image_file){
            $model->image_file = '';
            if($model->update()){
                @unlink(Yii::getAlias('@webroot').$model->image_file);
                $this->flash('success', Yii::t('easyii', 'Image cleared'));
            } else {
                $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
            }
        }
        return $this->back();
    }

    public function actionDelete($id)
    {
        if(($model = Item::findOne($id))){
            $model->delete();
        } else {
            $this->error = Yii::t('easyii', 'Not found');
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

    private function generateForm($fields, $data = null)
    {
        $result = '';
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
}