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
    public function actionCreate($slug = null, $parent = null)
    {
        $model = new Page;

        $fields = [];
        if($parent && ($parentPage = Page::findOne($parent))) {
            $fields = $parentPage->fields;
        } elseif(PageModule::setting('defaultFields')) {
            $settingFields = json_decode(PageModule::setting('defaultFields'));
            if(!json_last_error() && $settingFields && is_array($settingFields) && count($settingFields)){
                $fields = $settingFields;
            }
        }
        $model->fields = $fields;
        $model->slug = $slug;

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } else {
                $model->status = Page::STATUS_ON;
                $model->data = $this->parseData($model);

                if ($model->create(Yii::$app->request->post('parent', null))) {
                    $this->flash('success', Yii::t('easyii/page', 'Page created'));
                    return $this->redirect(['/admin/page', 'id' => $model->primaryKey]);
                } else {
                    $this->flash('error', Yii::t('easyii', 'Create error. {0}', $model->formatErrors()));
                    return $this->refresh();
                }
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'dataForm' => $this->generateForm($model->fields),
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
                $model->data = $this->parseData($model);

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
                'dataForm' => $this->generateForm($model->fields, $model->data),
            ]);
        }
    }

    /**
     * Copy page
     *
     * @param $id
     * @return array|string|\yii\web\Response
     * @throws \yii\web\HttpException
     */
    public function actionCopy($id)
    {
        $model = new Page();
        $model->load($this->findModel($id)->attributes, '');
        $model->slug = null;

        if ($model->create()) {
            $this->flash('success', Yii::t('easyii/page', 'Page copied'));
        } else {
            $this->flash('error', Yii::t('easyii', 'Create error. {0}', $model->formatErrors()));
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
        $model = $this->findModel($id);

        $children = $model->children()->all();
        $model->deleteWithChildren();
        foreach ($children as $child) {
            $child->afterDelete();
        }

        return $this->formatResponse(Yii::t('easyii/page', 'Page deleted'));
    }
}