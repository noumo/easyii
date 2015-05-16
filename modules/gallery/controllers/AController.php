<?php
namespace yii\easyii\modules\gallery\controllers;

use yii\easyii\components\CategoryController;
use yii\easyii\modules\gallery\models\Category;

class AController extends CategoryController
{
    public $categoryClass = 'yii\easyii\modules\gallery\models\Category';
    public $moduleName = 'gallery';
    public $viewRoute = '/a/photos';

    public function actionPhotos($id)
    {
        if(!($model = Category::findOne($id))){
            return $this->redirect(['/admin/'.$this->module->id]);
        }

        return $this->render('photos', [
            'model' => $model,
        ]);
    }
}