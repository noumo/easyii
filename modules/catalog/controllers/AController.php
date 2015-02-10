<?php
namespace yii\easyii\modules\catalog\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

use yii\easyii\components\Controller;
use yii\easyii\modules\catalog\models\Category;
use yii\easyii\helpers\Image;
use yii\easyii\behaviors\SortableController;
use yii\easyii\behaviors\StatusController;


class AController extends Controller
{
    public $rootActions = ['create', 'delete', 'fields'];

    public function behaviors()
    {
        return [
            [
                'class' => SortableController::className(),
                'model' => Category::className()
            ],
            [
                'class' => StatusController::className(),
                'model' => Category::className()
            ]
        ];
    }

    public function actionIndex()
    {
        $data = new ActiveDataProvider([
            'query' => Category::findWithItemCount()->sort(),
        ]);
        return $this->render('index', [
            'data' => $data
        ]);
    }

    public function actionCreate($slug = null)
    {
        $model = new Category;

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if(isset($_FILES) && $this->module->settings['categoryThumb']){
                    $model->thumb = UploadedFile::getInstance($model, 'thumb');
                    if($model->thumb && $model->validate(['thumb'])){
                        $model->thumb = Image::upload($model->thumb, 'catalog', $this->module->settings['categoryThumbWidth'], $this->module->settings['categoryThumbHeight'], $this->module->settings['categoryThumbCrop']);
                    }else{
                        $model->thumb = '';
                    }
                }

                $model->status = Category::STATUS_ON;

                if($model->save()){
                    $this->flash('success', Yii::t('easyii/catalog', 'Category created'));
                    return $this->redirect('/admin/catalog/a/fields/'.$model->primaryKey);
                }
                else{
                    $this->flash('error', Yii::t('easyii', 'Create error. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        }
        else {
            if($slug) $model->slug = $slug;

            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    public function actionEdit($id)
    {
        if(!($model = Category::findOne($id))){
            return $this->redirect('/admin/catalog');
        }

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if(isset($_FILES) && $this->module->settings['categoryThumb']){
                    $model->thumb = UploadedFile::getInstance($model, 'thumb');
                    if($model->thumb && $model->validate(['thumb'])){
                        $model->thumb = Image::upload($model->thumb, 'catalog', $this->module->settings['categoryThumbWidth'], $this->module->settings['categoryThumbHeight'], $this->module->settings['categoryThumbCrop']);
                    }else{
                        $model->thumb = $model->oldAttributes['thumb'];
                    }
                }
                if($model->save()){
                    $this->flash('success', Yii::t('easyii/catalog', 'Category updated'));
                }
                else{
                    $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
                }
                return $this->refresh();
            }
        }
        else {
            return $this->render('edit', [
                'model' => $model
            ]);
        }
    }

    public function actionFields($id)
    {
        if(!($model = Category::findOne($id))){
            return $this->redirect('/admin/catalog');
        }

        if (Yii::$app->request->post('save'))
        {
            $fields = Yii::$app->request->post('Field') ?: [];
            $result = [];

            foreach($fields as $field){
                $temp = json_decode($field);

                if( $temp === null && json_last_error() !== JSON_ERROR_NONE ||
                    empty($temp->name) ||
                    empty($temp->title) ||
                    empty($temp->type) ||
                    !($temp->name = trim($temp->name)) ||
                    !($temp->title = trim($temp->title)) ||
                    !array_key_exists($temp->type, Category::$fieldTypes)
                ){
                    continue;
                }
                $options = '';
                if($temp->type == 'select' || $temp->type == 'checkbox'){
                    if(empty($temp->options) || !($temp->options = trim($temp->options))){
                        continue;
                    }
                    $options = [];
                    foreach(explode(',', $temp->options) as $option){
                        $options[] = trim($option);
                    }
                }

                $result[] = [
                    'name' => \yii\helpers\Inflector::slug($temp->name),
                    'title' => $temp->title,
                    'type' => $temp->type,
                    'options' => $options
                ];
            }

            $model->fields = $result;

            if($model->save()){
                $this->flash('success', Yii::t('easyii/catalog', 'Category updated'));
            }
            else{
                $this->flash('error', Yii::t('easyii','Update error. {0}', $model->formatErrors()));
            }
            return $this->refresh();
        }
        else {
            return $this->render('fields', [
                'model' => $model
            ]);
        }
    }

    public function actionClearImage($id)
    {
        $model = Category::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('easyii', 'Not found'));
        }
        elseif($model->thumb){
            @unlink(Yii::getAlias('@webroot').$model->thumb);
            $model->thumb = '';
            $model->update();
            $this->flash('success', Yii::t('easyii/catalog', 'Image cleared'));
        }
        return $this->back();
    }

    public function actionDelete($id)
    {
        if(($model = Category::findOne($id))){
            $model->delete();
        } else{
            $this->error = Yii::t('easyii', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii/catalog', 'Category deleted'));
    }

    public function actionUp($id)
    {
        return $this->move($id, 'up');
    }

    public function actionDown($id)
    {
        return $this->move($id, 'down');
    }

    public function actionOn($id)
    {
        return $this->changeStatus($id, Category::STATUS_ON);
    }

    public function actionOff($id)
    {
        return $this->changeStatus($id, Category::STATUS_OFF);
    }
}