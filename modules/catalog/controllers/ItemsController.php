<?php
namespace yii\easyii\modules\catalog\controllers;

use Yii;
use yii\easyii\actions\ChangeStatusAction;
use yii\easyii\actions\ClearImageAction;
use yii\easyii\actions\SortAction;
use yii\easyii\actions\DeleteAction;
use yii\easyii\helpers\Upload;
use yii\validators\FileValidator;
use yii\web\UploadedFile;
use yii\helpers\Html;
use yii\easyii\components\Controller;
use yii\easyii\modules\catalog\models\Category;
use yii\easyii\modules\catalog\models\Item;
use yii\widgets\ActiveForm;

class ItemsController extends Controller
{
    static $RESTRICTED_EXTENSIONS = ['php', 'phtml', 'php5', 'htm', 'html', 'js', 'jsp', 'sh', 'exe', 'bat', 'com'];

    public function actions()
    {
        $className = Item::className();
        return [
            'delete' => [
                'class' => DeleteAction::className(),
                'model' => $className,
                'successMessage' => Yii::t('easyii/catalog', 'Item deleted')
            ],
            'clear-image' => [
                'class' => ClearImageAction::className(),
                'model' => $className
            ],
            'up' => [
                'class' => SortAction::className(),
                'model' => $className,
                'attribute' => 'time'
            ],
            'down' => [
                'class' => SortAction::className(),
                'model' => $className,
                'attribute' => 'time'
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
        if(!($model = Category::findOne($id))){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }


    public function actionCreate($id)
    {
        if(!($category = Category::findOne($id))){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        $model = new Item;

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else {
                $model->category_id = $category->primaryKey;
                $this->parseData($model);

                if ($model->save()) {
                    $this->flash('success', Yii::t('easyii/catalog', 'Item created'));
                    return $this->redirect(['/admin/'.$this->module->id.'/items/edit/', 'id' => $model->primaryKey]);
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
        if(!($model = Item::findOne($id))){
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
                    $this->flash('success', Yii::t('easyii/catalog', 'Item updated'));
                    return $this->redirect(['/admin/'.$this->module->id.'/items/edit', 'id' => $model->primaryKey]);
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

    public function actionPhotos($id)
    {
        if(!($model = Item::findOne($id))){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        return $this->render('photos', [
            'model' => $model,
        ]);
    }

    public function actionDeleteDataFile($file)
    {
        foreach(Item::find()->where(['like', 'data', $file])->all() as $model) {

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
            elseif ($field->type === 'boolean') {
                $result .= '<div class="checkbox"><label>'. Html::checkbox("Data[{$field->name}]", $value, ['uncheck' => 0]) .' '. $field->title .'</label></div>';
            }
            elseif ($field->type === 'select') {
                $options = ['' => Yii::t('easyii/catalog', 'Select')];
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
                $result .= '<div class="form-group"><label>'. $field->title .'</label>'. Html::fileInput("Data[{$field->name}]");
                if($value != ''){
                    $basename = basename($value);
                    $result .=
                        '<p>' .
                            Html::a($basename, [$value], ['target' => 'blank']) .
                            ' ' .
                            Html::a('<i class="glyphicon glyphicon-remove"></i>', ['/admin/catalog/items/delete-data-file', 'file' => $basename], ['class' => 'confirm-delete', 'data-reload' => 1, 'title' => Yii::t('easyii', 'Delete')]);
                        '</p>';
                }
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
                if($uploadInstance && !in_array($uploadInstance->extension, self::$RESTRICTED_EXTENSIONS) && $validator->validate($uploadInstance) && ($result = Upload::file($uploadInstance, 'catalog', false))) {
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