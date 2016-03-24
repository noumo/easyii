<?php
namespace yii\easyii\components;

use Yii;
use yii\easyii\actions\ChangeStatusAction;
use yii\easyii\actions\ClearImageAction;
use yii\easyii\actions\DeleteAction;
use yii\easyii\actions\SortByDateAction;
use yii\easyii\helpers\Image;
use yii\easyii\helpers\Upload;
use yii\easyii\widgets\DateTimePicker;
use yii\validators\FileValidator;
use yii\web\UploadedFile;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

class CategoryItemsController extends Controller
{
	/** @var string */
	public $categoryItemsClass;

	/** @var string */
	public $categoryClass;

	public $indexView = '@easyii/views/categoryItems/index';
	public $createView = '@easyii/views/categoryItems/create';
	public $editView = '@easyii/views/categoryItems/edit';

    public function actions()
    {
	    $className = $this->categoryItemsClass;
	    return [
            'delete' => [
                'class' => DeleteAction::className(),
                'model' => $className,
                'successMessage' => Yii::t('easyii', 'Item deleted')
            ],
            'clear-image' => [
                'class' => ClearImageAction::className(),
                'model' => $className
            ],
            'up' => [
                'class' => SortByDateAction::className(),
                'model' => $className,
            ],
            'down' => [
                'class' => SortByDateAction::className(),
                'model' => $className,
            ],
            'on' => [
                'class' => ChangeStatusAction::className(),
                'model' => $className,
                'status' => $className::STATUS_ON
            ],
            'off' => [
                'class' => ChangeStatusAction::className(),
                'model' => $className,
                'status' => $className::STATUS_OFF
            ],
        ];
    }

    public function actionIndex($id)
    {
	    $className = $this->categoryClass;

	    if(!($model = $className::findOne($id))){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }


    public function actionCreate($id)
    {
	    $className = $this->categoryClass;
	    if(!($category = $className::findOne($id))){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

	    $className = $this->categoryItemsClass;
	    $model = new $className;

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else {
                $model->category_id = $category->primaryKey;
                $this->parseData($model);

                if ($model->save()) {
                    $this->flash('success', Yii::t('easyii', 'Item created'));
                    return $this->redirect(['edit', 'id' => $model->primaryKey]);
                } else {
                    $this->flash('error', Yii::t('easyii', 'Create error. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        }
        else {
            return $this->render('create', [
                'model' => $model,
                'category' => $category,
                'dataForm' => $this->generateForm($category->fields)
            ]);
        }
    }

    public function actionEdit($id)
    {
	    $className = $this->categoryItemsClass;

	    if(!($model = $className::findOne($id))){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else {
                $this->parseData($model);

                if ($model->save()) {
                    $this->flash('success', Yii::t('easyii', 'Item updated'));
                    return $this->redirect(['edit', 'id' => $model->primaryKey]);
                } else {
                    $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        }
        else {
            return $this->render('edit', [
                'model' => $model,
                'dataForm' => $this->generateForm($model->category->fields, $model->data)
            ]);
        }
    }

    public function actionDeleteDataFile($file)
    {
	    $className = $this->categoryItemsClass;
	    foreach($className::find()->where(['like', 'data', $file])->all() as $model) {

            foreach ($model->data as $name => $value) {
                if (!is_array($value) && strpos($value, '/' . $file) !== false) {
                    Upload::delete($value);
                    $model->data->{$name} = '';
                }
            }
            $model->update();
        }
        return $this->formatResponse(Yii::t('easyii', 'Deleted'));
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
                $result .= '<div class="form-group"><label>'. $field->title .'</label>';
                $result .= \yii\easyii\widgets\Redactor::widget([
                    'name' => "Data[{$field->name}]",
                    'value' => $value,
                ]);
                $result .= '</div>';
            }
            elseif ($field->type === 'boolean') {
                $result .= '<div class="checkbox"><label>'. Html::checkbox("Data[{$field->name}]", $value, ['uncheck' => 0]) .' '. $field->title .'</label></div>';
            }
            elseif ($field->type === 'select') {
                $options = ['' => Yii::t('easyii', 'Select')];
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
            elseif ($field->type === 'file') {
                $result .= '<div class="form-group"><label>'. $field->title .'</label><p>';
                if($value != ''){
                    $basename = basename($value);
                    $isImage = preg_match('/\.(jpg|jpeg|png|gif|bmp)$/', $basename);

                    if($isImage) {
                        $result .= Html::a(Html::img(Image::thumb($value, 240, 180)), Upload::getFileUrl($value), ['class' => 'fancybox']);
                    } else {
                        $result .= Html::a($basename, [$value], ['target' => 'blank']);
                    }
                    $result .= ' ' . Html::a($isImage ? 'Удалить' : '<i class="glyphicon glyphicon-remove"></i>', ['/admin/' . $this->module->id . '/items/delete-data-file', 'file' => $basename], ['class' => 'confirm-delete', 'data-reload' => 1, 'title' => Yii::t('easyii', 'Delete')]);
                }
                $result .= '</p>' . Html::fileInput("Data[{$field->name}]"). '</div>';
            }
            elseif ($field->type === 'date') {
                $result .= '<div class="form-group"><label>'. $field->title .'</label>';
                $result .= DateTimePicker::widget(['name' => "Data[{$field->name}]", 'value' => $value]);
                $result .= '</div>';
            }
        }
        return $result;
    }

    private function parseData(&$model)
    {
        $data = Yii::$app->request->post('Data');

        if(isset($_FILES['Data']))
        {
            foreach($_FILES['Data']['name'] as $fieldName => $sourceName){
                $field = $model->category->getFieldByName($fieldName);
                $validator = new FileValidator(['extensions' => $field->options ? $field->options : null]);
                $uploadInstance = UploadedFile::getInstanceByName('Data['.$fieldName.']');
                if($uploadInstance && $validator->validate($uploadInstance) && ($result = Upload::file($uploadInstance, 'catalog', false))) {
                    if(!empty($model->data->{$fieldName})){
                        Upload::delete($model->data->{$fieldName});
                    }
                    $data[$fieldName] = $result;
                } else {
                    $data[$fieldName] = !empty($model->data->{$fieldName}) ? $model->data->{$fieldName} : '';
                }
            }
        }

        $model->data = $data;
    }
}