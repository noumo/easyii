<?php
namespace yii\easyii\components;

use Yii;
use yii\easyii\behaviors\SortableModel;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;
use yii\easyii\helpers\Image;

/**
 * Category controller component
 * @package yii\easyii\components
 */
class CategoryController extends Controller
{
    /** @var string */
    public $categoryClass;

    /** @var  string */
    public $moduleName;

    /** @var string  */
    public $viewRoute = '/items';

    /**
     * Categories list
     *
     * @return string
     */
    public function actionIndex()
    {
        $class = $this->categoryClass;
        return $this->render('@easyii/views/category/index', [
            'cats' => $class::cats()
        ]);
    }

    /**
     * Create form
     *
     * @param null $parent
     * @return array|string|\yii\web\Response
     * @throws \yii\web\HttpException
     */
    public function actionCreate($parent = null)
    {
        $class = $this->categoryClass;
        $model = new $class;

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->isAjax){
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            else{
                if(isset($_FILES) && $this->module->settings['categoryThumb']){
                    $model->image = UploadedFile::getInstance($model, 'image');
                    if($model->image && $model->validate(['image'])){
                        $model->image = Image::upload($model->image, $this->moduleName);
                    } else {
                        $model->image = '';
                    }
                }

                $model->status = $class::STATUS_ON;

                $parent = (int)Yii::$app->request->post('parent', null);
                if($parent > 0 && ($parentCategory = $class::findOne($parent))){
                    $model->order_num = $parentCategory->order_num;
                    $model->appendTo($parentCategory);
                } else {
                    $model->attachBehavior('sortable', SortableModel::className());
                    $model->makeRoot();
                }

                if(!$model->hasErrors()){
                    $this->flash('success', Yii::t('easyii', 'Category created'));
                    return $this->redirect(['/admin/'.$this->moduleName, 'id' => $model->primaryKey]);
                }
                else{
                    $this->flash('error', Yii::t('easyii', 'Create error. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        }
        else {
            return $this->render('@easyii/views/category/create', [
                'model' => $model,
                'parent' => $parent
            ]);
        }
    }

    /**
     * Edit form
     *
     * @param $id
     * @return array|string|\yii\web\Response
     * @throws \yii\web\HttpException
     */
    public function actionEdit($id)
    {
        $class = $this->categoryClass;

        if(!($model = $class::findOne($id))){
            return $this->redirect(['/admin/' . $this->moduleName]);
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
                        $model->image = Image::upload($model->image, $this->moduleName);
                    }else{
                        $model->image = $model->oldAttributes['image'];
                    }
                }
                if($model->save()){
                    $this->flash('success', Yii::t('easyii', 'Category updated'));
                }
                else{
                    $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
                }
                return $this->refresh();
            }
        }
        else {
            return $this->render('@easyii/views/category/edit', [
                'model' => $model
            ]);
        }
    }

    /**
     * Remove category image
     *
     * @param $id
     * @return \yii\web\Response
     */
    public function actionClearImage($id)
    {
        $class = $this->categoryClass;
        $model = $class::findOne($id);

        if($model === null){
            $this->flash('error', Yii::t('easyii', 'Not found'));
        }
        elseif($model->image){
            $model->image = '';
            if($model->update()){
                $this->flash('success', Yii::t('easyii', 'Image cleared'));
            } else {
                $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
            }
        }
        return $this->back();
    }

    /**
     * Delete the category by ID
     *
     * @param $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $class = $this->categoryClass;
        if(($model = $class::findOne($id))){
            $children = $model->children()->all();
            $model->deleteWithChildren();
            foreach($children as $child) {
                $child->afterDelete();
            }
        } else {
            $this->error = Yii::t('easyii', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii', 'Category deleted'));
    }

    /**
     * Move category one level up up
     *
     * @param $id
     * @return \yii\web\Response
     */
    public function actionUp($id)
    {
        return $this->move($id, 'up');
    }

    /**
     * Move category one level down
     *
     * @param $id
     * @return \yii\web\Response
     */
    public function actionDown($id)
    {
        return $this->move($id, 'down');
    }

    /**
     * Activate category action
     *
     * @param $id
     * @return mixed
     */
    public function actionOn($id)
    {
        $class = $this->categoryClass;
        return $this->changeStatus($id, $class::STATUS_ON);
    }

    /**
     * Activate category action
     *
     * @param $id
     * @return mixed
     */
    public function actionOff($id)
    {
        $class = $this->categoryClass;
        return $this->changeStatus($id, $class::STATUS_OFF);
    }

    /**
     * Move category up/down
     *
     * @param $id
     * @param $direction
     * @return \yii\web\Response
     * @throws \Exception
     */
    private function move($id, $direction)
    {
        $modelClass = $this->categoryClass;

        if(($model = $modelClass::findOne($id)))
        {
            $up = $direction == 'up';
            $orderDir = $up ? SORT_ASC : SORT_DESC;

            if($model->depth == 0){

                $swapCat = $modelClass::find()->where([$up ? '>' : '<', 'order_num', $model->order_num])->orderBy(['order_num' => $orderDir])->one();
                if($swapCat)
                {
                    $modelClass::updateAll(['order_num' => '-1'], ['order_num' => $swapCat->order_num]);
                    $modelClass::updateAll(['order_num' => $swapCat->order_num], ['order_num' => $model->order_num]);
                    $modelClass::updateAll(['order_num' => $model->order_num], ['order_num' => '-1']);
                    $model->trigger(\yii\db\ActiveRecord::EVENT_AFTER_UPDATE);
                }
            } else {
                $where = [
                    'and',
                    ['tree' => $model->tree],
                    ['depth' => $model->depth],
                    [($up ? '<' : '>'), 'lft', $model->lft]
                ];

                $swapCat = $modelClass::find()->where($where)->orderBy(['lft' => ($up ? SORT_DESC : SORT_ASC)])->one();
                if($swapCat)
                {
                    if($up) {
                        $model->insertBefore($swapCat);
                    } else {
                        $model->insertAfter($swapCat);
                    }

                    $swapCat->update();
                    $model->update();
                }
            }
        }
        else {
            $this->flash('error', Yii::t('easyii', 'Not found'));
        }
        return $this->back();
    }

    /**
     * Change category status
     *
     * @param $id
     * @param $status
     * @return mixed
     */
    public function changeStatus($id, $status)
    {
        $modelClass = $this->categoryClass;
        $ids = [];

        if(($model = $modelClass::findOne($id))){
            $ids[] = $model->primaryKey;
            foreach($model->children()->all() as $child){
                $ids[] = $child->primaryKey;
            }
            $modelClass::updateAll(['status' => $status], ['in', 'category_id', $ids]);
            $model->trigger(\yii\db\ActiveRecord::EVENT_AFTER_UPDATE);
        }
        else{
            $this->error = Yii::t('easyii', 'Not found');
        }

        return $this->formatResponse(Yii::t('easyii', 'Status successfully changed'));
    }
}