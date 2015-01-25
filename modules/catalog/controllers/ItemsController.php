<?php
namespace yii\easyii\modules\catalog\controllers;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\Html;

use yii\easyii\components\Controller;
use yii\easyii\modules\catalog\models\Category;
use yii\easyii\modules\catalog\models\Item;
use yii\easyii\helpers\Image;
use yii\easyii\behaviors\SortableController;

class ItemsController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => SortableController::className(),
                'model' => Item::className(),
            ]
        ];
    }

    public function actionIndex($id)
    {
        if(!($model = Category::findOne($id))){
            return $this->redirect('/admin/catalog');
        }

        return $this->render('index', [
            'model' => $model
        ]);
    }


    public function actionCreate($id)
    {
        if(!($category = Category::findOne($id))){
            return $this->redirect('/admin/catalog');
        }

        $model = new Item;

        if ($model->load(Yii::$app->request->post())) {
            $model->category_id = $category->primaryKey;
            $model->data = Yii::$app->request->post('Data');

            if(isset($_FILES) && $this->module->settings['itemThumb']){
                $model->thumb = UploadedFile::getInstance($model, 'thumb');
                if($model->thumb && $model->validate(['thumb'])){
                    $model->thumb = Image::upload($model->thumb, 'catalog', $this->module->settings['itemThumbWidth'], $this->module->settings['itemThumbHeight'], $this->module->settings['itemThumbCrop']);
                }else{
                    $model->thumb = '';
                }
            }

            if(!$model->slug && $this->module->settings['itemAutoSlug']){
                $model->slug = \yii\easyii\helpers\Data::generateSlug($model->title);
            }

            if($model->save()){
                $this->flash('success', Yii::t('easyii/catalog', 'Item created'));
                return $this->redirect('/admin/catalog/items/edit/'.$model->primaryKey);
            }
            else{
                $this->flash('error', Yii::t('easyii', 'Create error. {0}', $model->formatErrors()));
                return $this->refresh();
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
            return $this->redirect('/admin/catalog');
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->data = Yii::$app->request->post('Data');

            if(isset($_FILES) && $this->module->settings['itemThumb']){
                $model->thumb = UploadedFile::getInstance($model, 'thumb');
                if($model->thumb && $model->validate(['thumb'])){
                    $model->thumb = Image::upload($model->thumb, 'catalog', $this->module->settings['itemThumbWidth'], $this->module->settings['itemThumbHeight'], $this->module->settings['itemThumbCrop']);
                }else{
                    $model->thumb = $model->oldAttributes['thumb'];
                }
            }

            if($model->save()){
                $this->flash('success', Yii::t('easyii/catalog', 'Item updated'));
                return $this->redirect('/admin/catalog/items/edit/'.$model->primaryKey);
            }
            else{
                $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
                return $this->refresh();
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
            return $this->redirect('/admin/catalog');
        }

        return $this->render('photos', [
            'model' => $model,
        ]);
    }

    public function actionClearImage($id)
    {
        if(($model = Item::findOne($id)))
        {
            $model->updateAttributes(['thumb' => '']);
            @unlink(Yii::getAlias('@webroot').$model->thumb);
        } else{
            $this->error = Yii::t('easyii', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii/catalog', 'Image cleared'));
    }

    public function actionDelete($id)
    {
        if(($model = Item::findOne($id))){
            $model->delete();
        } else{
            $this->error = Yii::t('easyii', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii/catalog', 'Item deleted'));
    }

    public function actionUp($id, $category_id)
    {
        return $this->move($id, 'up', ['category_id' => $category_id]);
    }

    public function actionDown($id, $category_id)
    {
        return $this->move($id, 'down', ['category_id' => $category_id]);
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
        }
        return $result;
    }
}