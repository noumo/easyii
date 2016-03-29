<?php
namespace yii\easyii\modules\page\controllers;

use Yii;
use yii\easyii\behaviors\Fields;
use yii\easyii\behaviors\SortableModel;
use yii\easyii\components\CategoryController;
use yii\easyii\modules\page\PageModule;
use yii\widgets\ActiveForm;
use yii\easyii\modules\page\models\Page;

class AController extends CategoryController
{
    public $categoryClass = 'yii\easyii\modules\page\models\Page';
    public $modelClass = 'yii\easyii\modules\page\models\Page';

    public function behaviors()
    {
        return [
            'fields' => Fields::className()
        ];
    }

    public function actionIndex()
    {
        return $this->render('index', [
            'pages' => Page::cats()
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
        $model = new Page;

        $fields = [];
        if($parent && ($parentPage = Page::findOne($parent))) {
            $fields = $parentPage->fields;
        } elseif (PageModule::setting('defaultFields')) {
            $fields = PageModule::setting('defaultFields');
        }

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                $model->status = Page::STATUS_ON;
                $model->data = $this->fields->parseData($model);

                $parent = (int)Yii::$app->request->post('parent', null);
                if ($parent > 0 && ($parentCategory = Page::findOne($parent))) {
                    $model->order_num = $parentCategory->order_num;
                    $model->appendTo($parentCategory);
                } else {
                    $model->attachBehavior('sortable', SortableModel::className());
                    $model->makeRoot();
                }

                if (!$model->hasErrors()) {
                    $this->flash('success', Yii::t('easyii', 'Category created'));
                    return $this->redirect(['/admin/page', 'id' => $model->primaryKey]);
                } else {
                    $this->flash('error', Yii::t('easyii', 'Create error. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'dataForm' => $this->fields->generateForm($model->fields),
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
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                $model->data = $this->fields->parseData($model);

                if ($model->save()) {
                    $this->flash('success', Yii::t('easyii/page', 'Page updated'));
                } else {
                    $this->flash('error', Yii::t('easyii', 'Update error. {0}', $model->formatErrors()));
                }
                return $this->refresh();
            }
        } else {
            return $this->render('edit', [
                'model' => $model,
                'dataForm' => $this->fields->generateForm($model->fields, $model->data),
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
        $model = $this->findModel($id);

        $children = $model->children()->all();
        $model->deleteWithChildren();
        foreach ($children as $child) {
            $child->afterDelete();
        }

        return $this->formatResponse(Yii::t('easyii/page', 'Page deleted'));
    }
}