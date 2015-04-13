<?php
namespace yii\easyii\modules\article\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii\behaviors\SortableControllerNS;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

use yii\easyii\components\Controller;
use yii\easyii\modules\article\models\Category;
use yii\easyii\helpers\Image;
use yii\easyii\behaviors\SortableController;
use yii\easyii\behaviors\StatusController;


class AController extends Controller
{
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
                        $model->image = Image::upload($model->image, 'article');
                    }else{
                        $model->image = '';
                    }
                }

                $model->status = Category::STATUS_ON;

                $parent = Yii::$app->request->post('parent', null);
                if($parent && ($parentCategory = Category::findOne((int)$parent))){
                    $model->appendTo($parentCategory);
                    $model->order_num = $parentCategory->order_num;
                } else {
                    $model->makeRoot();
                }

                if($model->save()){

                    $this->flash('success', Yii::t('easyii/article', 'Category created'));
                    return $this->redirect(['/admin/article/items/index', 'id' => $model->primaryKey]);
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
            return $this->redirect(['/admin/article/']);
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
                        $model->image = Image::upload($model->image, 'article');
                    }else{
                        $model->image = $model->oldAttributes['image'];
                    }
                }
                if($model->save()){
                    $this->flash('success', Yii::t('easyii/article', 'Category updated'));
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

    public function actionClearImage($id)
    {
        $model = Category::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('easyii', 'Not found'));
        }
        elseif($model->image){
            $model->image = '';
            if($model->update()){
                $this->flash('success', Yii::t('easyii/article', 'Image cleared'));
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
        return $this->formatResponse(Yii::t('easyii/article', 'Category deleted'));
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