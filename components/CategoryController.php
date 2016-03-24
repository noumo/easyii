<?php
namespace yii\easyii\components;

use Yii;
use yii\easyii\actions\ChangeStatusAction;
use yii\easyii\actions\ClearImageAction;
use yii\easyii\actions\MoveAction;
use yii\easyii\behaviors\SortableModel;
use yii\widgets\ActiveForm;

/**
 * Category controller component
 * @package yii\easyii\components
 */
class CategoryController extends Controller
{
    //Todo: Better $this->module->id
    /** @var  string */
    public $moduleName;

    //Todo: Remove the slash!
    /** @var string  */
	public $viewRoute = '/items';

    public function actions()
    {
        $className = $this->categoryClass;
        return [
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
        $class = $this->categoryClass;
        $model = new $class;

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                $model->status = $class::STATUS_ON;

                $parent = (int)Yii::$app->request->post('parent', null);
                if ($parent > 0 && ($parentCategory = $class::findOne($parent))) {
                    $model->order_num = $parentCategory->order_num;
                    $model->appendTo($parentCategory);
                } else {
                    $model->attachBehavior('sortable', SortableModel::className());
                    $model->makeRoot();
                }

                if (!$model->hasErrors()) {
                    $this->flash('success', Yii::t('easyii', 'Category created'));
                    return $this->redirect(['/admin/'.$this->module->id, 'id' => $model->primaryKey]);
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
        $modelClass::updateAll(['status' => $status], ['in', 'category_id', $ids]);
        $model->trigger(\yii\db\ActiveRecord::EVENT_AFTER_UPDATE);

        return $this->formatResponse(Yii::t('easyii', 'Status successfully changed'));
    }
}
