<?php
namespace yii\easyii\modules\catalog\controllers;

use Yii;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;
use yii\easyii\components\Controller;
use yii\easyii\modules\catalog\models\Category;
use yii\easyii\helpers\Image;
use yii\easyii\behaviors\SortableControllerNS;
use yii\easyii\behaviors\StatusController;


class AController extends Controller
{
    public $rootActions = ['create', 'delete', 'fields'];

    public function behaviors()
    {
        return [
            [
                'class' => StatusController::className(),
                'model' => Category::className()
            ],
            [
                'class' => SortableControllerNS::className(),
                'model' => Category::className()
            ]
        ];
    }

    public function actionIndex()
    {
        $tree = Category::getTree();
        return $this->render('index', [
            'tree' => $tree
        ]);
    }

    public function actionCreate($parent = null)
    {
        $model = new Category;

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if(isset($_FILES) && $this->module->settings['categoryThumb']){
                    $model->image = UploadedFile::getInstance($model, 'image');
                    if($model->image && $model->validate(['image'])){
                        $model->image = Image::upload($model->image, 'catalog');
                    }else{
                        $model->image = '';
                    }
                }

                $model->status = Category::STATUS_ON;

                $parent = Yii::$app->request->post('parent', null);
                if($parent && ($parentCategory = Category::findOne((int)$parent))){
                    $model->appendTo($parentCategory);
                    $model->order_num = $parentCategory->order_num;
                    $model->fields = $parentCategory->fields;
                } else {
                    $model->makeRoot();
                }

                if($model->save()){
                    $action = $model->depth === 0 ? 'a/fields' : '';
                    $this->flash('success', Yii::t('easyii/catalog', 'Category created'));
                    return $this->redirect(['/admin/catalog/'.$action, 'id' => $model->primaryKey]);
                }
                else{
                    $this->flash('error', Yii::t('easyii', 'Create error. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        }
        else {
            return $this->render('create', [
                'model' => $model,
                'parent' => $parent
            ]);
        }
    }

    public function actionEdit($id)
    {
        if(!($model = Category::findOne($id))){
            return $this->redirect(['/admin/catalog']);
        }

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if(isset($_FILES) && $this->module->settings['categoryThumb']){
                    $model->image = UploadedFile::getInstance($model, 'image');
                    if($model->image && $model->validate(['image'])){
                        $model->image = Image::upload($model->image, 'catalog');
                    }else{
                        $model->image = $model->oldAttributes['image'];
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
            return $this->redirect(['/admin/catalog']);
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
                if($model->depth == 0) {
                    Category::updateAll(['fields' => $model->fields], ['tree' => $model->primaryKey]);
                }

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
        elseif($model->image){
            $model->image = '';
            if($model->update()){
                @unlink(Yii::getAlias('@webroot').$model->image);
                $this->flash('success', Yii::t('easyii/catalog', 'Image cleared'));
            } else {
                $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
            }
        }
        return $this->back();
    }

    public function actionDelete($id)
    {
        if(($model = Category::findOne($id))){
            $model->deleteWithChildren();
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