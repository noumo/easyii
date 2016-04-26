<?php
namespace yii\easyii\components;

use Yii;
use yii\easyii\actions\ChangeStatusAction;
use yii\easyii\actions\ClearImageAction;
use yii\easyii\actions\FieldsAction;
use yii\easyii\helpers\Upload;
use yii\widgets\ActiveForm;

/**
 * Category controller component
 * @package yii\easyii\components
 */
class CategoryController extends Controller
{
    /** @var  string */
    public $moduleName;

    /** @var string */
    public $viewRoute = '/items';

    public $rootActions = ['fields'];

    public function actions()
    {
        $className = $this->categoryClass;
        return [
            'fields' => [
                'class' => FieldsAction::className(),
                'model' => $className
            ],
            'clear-image' => [
                'class' => ClearImageAction::className(),
                'model' => $className
            ],
            'on' => [
                'class' => ChangeStatusAction::className(),
                'model' => $className,
            ],
            'off' => [
                'class' => ChangeStatusAction::className(),
                'model' => $className,
            ],
        ];
    }

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
        /** @var CategoryModel $model */
        $class = $this->categoryClass;
        $model = new $class;

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                $model->status = $class::STATUS_ON;

                if ($model->create(Yii::$app->request->post('parent', null))) {
                    $this->flash('success', Yii::t('easyii', 'Category created'));
                    return $this->redirect(['/admin/' . $this->moduleName, 'id' => $model->primaryKey]);
                } else {
                    $this->flash('error', Yii::t('easyii', 'Create error. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        } else {
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
        $model = $this->findCategory($id);

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                if ($model->save()) {
                    $this->flash('success', Yii::t('easyii', 'Category updated'));
                } else {
                    $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
                }
                return $this->refresh();
            }
        } else {
            return $this->render('@easyii/views/category/edit', [
                'model' => $model
            ]);
        }
    }

    /**
     * Delete the category by ID
     *
     * @param $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findCategory($id);
        $children = $model->children()->all();
        $model->deleteWithChildren();
        foreach ($children as $child) {
            $child->afterDelete();
        }

        return $this->formatResponse(Yii::t('easyii', 'Category deleted'));
    }

    public function actionDeleteDataFile($file)
    {
        $itemClass = $this->modelClass;
        foreach($itemClass::find()->where(['like', 'data', $file])->all() as $model) {

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
     * Move category up/down
     *
     * @param $id
     * @param $direction
     * @return \yii\web\Response
     * @throws \Exception
     */
    private function move($id, $direction)
    {
        $model = $this->findCategory($id);
        $modelClass = $this->categoryClass;

        $up = $direction == 'up';
        $orderDir = $up ? SORT_ASC : SORT_DESC;

        if ($model->depth == 0) {

            $swapCat = $modelClass::find()->where([$up ? '>' : '<', 'order_num', $model->order_num])->orderBy(['order_num' => $orderDir])->one();
            if ($swapCat) {
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
            if ($swapCat) {
                if ($up) {
                    $model->insertBefore($swapCat);
                } else {
                    $model->insertAfter($swapCat);
                }

                $swapCat->update();
                $model->update();
            }
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
        $model = $this->findCategory($id);
        $modelClass = $this->categoryClass;
        $ids = [$model->primaryKey];

        foreach ($model->children()->all() as $child) {
            $ids[] = $child->primaryKey;
        }
        $modelClass::updateAll(['status' => $status], ['in', 'id', $ids]);
        $model->trigger(\yii\db\ActiveRecord::EVENT_AFTER_UPDATE);

        return $this->formatResponse(Yii::t('easyii', 'Status successfully changed'));
    }
}