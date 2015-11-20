<?php
namespace yii\easyii\modules\gallery\controllers;

use yii\easyii\components\CategoryController;

class AController extends CategoryController
{
    public $categoryClass = 'yii\easyii\modules\gallery\models\Category';
    public $moduleName = 'gallery';
    public $viewRoute = '/a/photos';

    public function actionPhotos($id)
    {
        return $this->render('photos', [
            'model' => $this->findCategory($id),
        ]);
    }
}